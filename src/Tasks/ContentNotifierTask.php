<?php

namespace SilverStripe\ContentNotifier\Tasks;

use SilverStripe\ContentNotifier\Model\ContentNotifierEmail;
use SilverStripe\ContentNotifier\Model\ContentNotifierQueue;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Dev\BuildTask;

class ContentNotifierTask extends BuildTask
{
    private static $segment = 'ContentNotifierTask';

    protected $title = 'New and updated content notification task';

    protected $description = 'Collates and sends items to notify users about';

    public function run($request)
    {
        $queue = ContentNotifierQueue::get_unnotified();
        if ($queue->exists()) {
            $count = $queue->count();
            $e = Injector::inst()->create(ContentNotifierEmail::class)
                ->setRecords($queue)
                ->send();

            echo "Sent $count notifications\n";
        } else {
            echo "No queued notifications\n";
        }
    }
}
