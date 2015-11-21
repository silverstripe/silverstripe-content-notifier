<?php


class ContentNotifierTask extends BuildTask
{
    protected $title = 'New and updated content notification task';

    protected $description = 'Collates and sends items to notify users about';

    public function run($request)
    {
        $queue = ContentNotifierQueue::get_unnotified();
        if ($queue->exists()) {
            $count = $queue->count();
            $e = Injector::inst()->create('ContentNotifierEmail')
                    ->setRecords($queue)
                    ->send();

            echo "Sent $count notifications\n";
        } else {
            echo "No queued notifications\n";
        }
    }
}
