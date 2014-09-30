<?php


class ContentNotifierTask extends CliController
{

    public function index() {
        $queue = ContentNotifierQueue::get_unnotified();
        if($queue->exists()) {
            $count = $queue->count();
            $e = Injector::inst()->create('ContentNotifierEmail')
                    ->setRecords($queue)
                    ->send();

            echo "Sent $count notifications\n";
        }

        else {
            echo "No queued notifications\n";
        }

    }
}