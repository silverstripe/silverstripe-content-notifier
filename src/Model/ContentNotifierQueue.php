<?php

namespace SilverStripe\ContentNotifier\Model;

use SilverStripe\Control\Controller;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\LiteralField;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Permission;
use UncleCheese\BetterButtons\Actions\BetterButtonCustomAction;
use UncleCheese\BetterButtons\Actions\BetterButtonLink;

class ContentNotifierQueue extends DataObject
{
    private static $db = array(
        'RecordClass' => 'Varchar',
        'RecordID' => 'Int',
        'Event' => "Enum('CREATED,UPDATED')",
        'HasNotified' => 'Boolean'
    );

    private static $summary_fields = array(
        'Created' => 'Created.Nice',
        'Event' => 'Event',
        'RecordClass' => 'Content type',
        'Status' => 'Status'
    );

    private static $better_buttons_actions = array(
        'approve',
        'deny'
    );

    private static $searchable_fields = array();

    private static $default_sort = "Created DESC";

    private static $table_name = 'ContentNotifierQueue';

    public static function get_unnotified()
    {
        return self::get()->filter(array(
            'HasNotified' => false
        ));
    }

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        if (!$this->getRecord()) {
            return FieldList::create();
        }

        $fields = $this->getRecord()->getCMSFields();
        $fields->unshift(
            new LiteralField("stat", "<h3 style='margin-left:10px;'>Status: " . $this->getRecord()->getStatus()."</h3>")
        );

        // Create a dummy form so we can get access to loadDataFrom(). :-(
        return Form::create(Controller::curr(), "dummy", $fields, FieldList::create())
            ->loadDataFrom($this->getRecord())
            ->Fields()
            ->makeReadonly();
    }

    public function Category()
    {
        return Injector::inst()->get($this->RecordClass)->plural_name();
    }

    public function getRecord()
    {
        $class = Injector::inst()->get($this->RecordClass);
        return DataList::create($class->class)->byID($this->RecordID);
    }

    public function getTitle()
    {
        if ($this->getRecord()) {
            return "[{$this->RecordClass}] " . $this->getRecord()->getTitle();
        }
    }

    public function getBetterButtonsActions()
    {
        $fields = parent::getBetterButtonsActions();
        if (!$this->getRecord()) {
            return $fields;
        }

        if ($this->getRecord()->ContentNotifierApproved) {
            $fields->push(
                BetterButtonCustomAction::create('deny', 'Deny')
                    ->setRedirectType(BetterButtonCustomAction::REFRESH)
                    ->setSuccessMessage('Denied for publication')
            );
        } else {
            $fields->push(
                BetterButtonCustomAction::create('approve', 'Approve')
                    ->setRedirectType(BetterButtonCustomAction::REFRESH)
                    ->setSuccessMessage('Approved for publication')
            );
        }

        $fields->push(
            new BetterButtonLink(
                'Edit this ' . $this->RecordClass,
                $this->getRecord()->getContentNotifierLink()
            )
        );

        return $fields;
    }

    public function getStatus()
    {
        if ($this->getRecord()) {
            return $this->getRecord()->getStatus();
        }
    }

    public function approve()
    {
        if ($this->getRecord()) {
            $this->getRecord()->approve();
        }
    }

    public function deny()
    {
        if ($this->getRecord()) {
            $this->getRecord()->deny();
        }
    }

    public function canEdit($member = null)
    {
        return Permission::check("CMS_ACCESS_ContentNotifierAdmin");
    }

    public function canView($member = null)
    {
        return Permission::check("CMS_ACCESS_ContentNotifierAdmin");
    }

    public function canDelete($member = null)
    {
        return Permission::check("CMS_ACCESS_ContentNotifierAdmin");
    }

    public function canCreate($member = null, $context = [])
    {
        return false;
    }
}
