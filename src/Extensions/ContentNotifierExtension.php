<?php

namespace SilverStripe\ContentNotifier\Extensions;

use RuntimeException;
use BetterButtonCustomAction;
use SilverStripe\Admin\LeftAndMain;
use SilverStripe\Core\ClassInfo;
use SilverStripe\Core\Config\Config;
use SilverStripe\ContentNotifier\Model\ContentNotifierEmail;
use SilverStripe\ContentNotifier\Model\ContentNotifierQueue;
use SilverStripe\Control\Director;
use SilverStripe\Control\Controller;
use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\Queries\SQLSelect;
use SilverStripe\Security\Permission;

class ContentNotifierExtension extends DataExtension
{
    private static $db = array(
        'ContentNotifierApproved' => 'Boolean'
    );

    private static $better_buttons_actions = array(
        'approve',
        'deny'
    );

    protected static $filter_unapproved = true;

    public static function get_extra_config($class, $extension, $args)
    {
        if (!ClassInfo::classImplements($class, "SilverStripe\\ContentNotifier\\ContentNotifier")) {
            throw new RuntimeException("$class must implement ContentNotifier to be used by the ContentNotifierExtension");
        }
    }


    public static function enable_filtering()
    {
        self::$filter_unapproved = true;
    }


    public static function disable_filtering()
    {
        self::$filter_unapproved = false;
    }


    public function updateBetterButtonsActions($actions)
    {
        if ($this->owner->ContentNotifierApproved) {
            $actions->push(new BetterButtonCustomAction(
                'deny',
                'Deny'
            ));
        } else {
            $actions->push(new BetterButtonCustomAction(
                'approve',
                'Approve'
            ));
        }
    }

    protected function resolve($approved)
    {
        $this->owner->ContentNotifierApproved = $approved;
        $this->owner->write();

        if ($this->getSetting('delete_on_resolve')) {
            if ($object = $this->getQueue()) {
                $object->delete();
            }
        }
    }

    /**
     * Returns the ContentNotifier setting (note: not fully qualified)
     *
     * @param  string $setting
     * @return string|false
     */
    protected function getSetting($setting)
    {
        $config = Config::inst()->get($this->owner->class, 'ContentNotifier');

        return isset($config[$setting]) ? $config[$setting] : false;
    }

    protected function shouldAutoApprove($type)
    {
        $autoApprove = $this->getSetting('auto_approve');
        if ($autoApprove) {
            return ($autoApprove == "*") || (strtolower($autoApprove) == strtolower($type));
        }

        return false;
    }

    public function approve()
    {
        $this->resolve(true);
    }

    public function deny()
    {
        $this->resolve(false);
    }

    public function EmailSummary()
    {
        $template = $this->getSetting('email_notifier_template') ?: Config::inst()
            ->get(ContentNotifier::class, 'item_template');

        return $this->owner->renderWith($template);
    }

    public function getStatus()
    {
        return $this->owner->ContentNotifierApproved
            ? _t('ContentNotifier.APPROVED', 'APPROVED')
            : _t('ContentNotifier.UNAPPROVED', 'UNAPPROVED');
    }

	public function onBeforeWrite()
    {
		// Prevent CMS actions or updates being overridden
		if ($this->checkPermission()) {
			$this->owner->ContentNotifierApproved = true;
		}

		// If creating a dataobject for the first time, auto-approve if allowed
		if (!$this->owner->isInDB()) {
			$this->owner->isCreating = true;

			// New records can approve themselves
			if ($this->shouldAutoApprove('CREATED')) {
				$this->owner->ContentNotifierApproved = true;
			}

			return;
		}

		// If editing a record, allow auto unapproval
		if (!$this->owner->isChanged('ContentNotifierApproved')) {
			// Adjust approvel only if not changed explicitly
			$this->owner->ContentNotifierApproved = $this->shouldAutoApprove('UPDATED');
		}
	}

	public function onAfterWrite()
    {
		// Trigger events after approval state changes.
		if ($this->owner->isChanged('ContentNotifierApproved', 2)) {
			if ($this->owner->ContentNotifierApproved) {
				$this->owner->invokeWithExtensions('onAfterContentNotifierApprove');
			} else {
				$this->owner->invokeWithExtensions('onAfterContentNotifierUnapprove');
			}
		}

		// Note: this has an effect that privileged user's showcase submissions will not show up in the queue.
		if ($this->checkPermission()) {
            return;
        }

		if ($this->owner->isCreating) {
			$this->createQueue('CREATED');
		} elseif ($this->owner->isChanged()) {
			// Clear any existing entry
			if ($queue = $this->getQueue('UPDATED')) {
				$queue->delete();
			}
			$this->createQueue('UPDATED');
		}

		if (!$this->getSetting('batch_email')) {
			$email = ContentNotifierEmail::create();
			$email->setRecords(ContentNotifierQueue::get_unnotified());
			$email->send();
		}
	}

    public function onAfterDelete()
    {
        ContentNotifierQueue::get()->filter(array(
            'RecordClass' => $this->owner->class,
            'RecordID' => $this->owner->ID ?: 0
        ))->removeAll();
    }

    public function augmentSQL(SQLSelect $query, DataQuery $dataQuery = null)
    {
        if (!$this->checkPermission() && self::$filter_unapproved) {
            $query->addWhere("ContentNotifierApproved = 1");
        }
    }

    protected function createQueue($event)
    {
        return ContentNotifierQueue::create(array(
            'RecordClass' => $this->owner->class,
            'Event' => $event,
            'RecordID' => $this->owner->ID
        ))->write();
    }

    public function getQueue($event = null)
    {
        $list = ContentNotifierQueue::get()->filter(array(
            'RecordClass' => $this->owner->class,
            'RecordID' => $this->owner->ID ?: 0
        ));

        if ($event) {
            $list = $list->filter('Event', $event);
        }
        return $list->first();
    }

	protected function checkPermission()
    {
		if (Director::is_cli()) {
            return false;
        }

		$perm = Config::inst()->get(__CLASS__, 'admin_permission');
		$cms = is_subclass_of(Controller::curr()->class, LeftAndMain::class);

		return Permission::check($perm) || $cms;
	}
}
