<?php

namespace SilverStripe\ContentNotifier\Admin;

use SilverStripe\Admin\ModelAdmin;
use SilverStripe\ContentNotifier\Model\ContentNotifierQueue;

class ContentNotifierAdmin extends ModelAdmin
{
    private static $managed_models = array(
        ContentNotifierQueue::class => array(
            'title' => 'Notifications'
        )
    );

    private static $menu_title = 'Content Notifications';

    private static $url_segment = 'content-notifications';
}
