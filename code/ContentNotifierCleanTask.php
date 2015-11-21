<?php

class ContentNotifierCleanTask extends BuildTask
{
    public function run($request)
    {
        $action = $request->getVar('action');
        $count = 0;
        switch ($action) {
            case "all":
                $count = ContentNotifierQueue::get()->count();
                ContentNotifierQueue::get()->removeAll();
            break;

            case "approved":
                foreach (ContentNotifierQueue::get() as $q) {
                    if ($rec = $q->getRecord()) {
                        if ($rec->ContentNotifierApproved) {
                            $q->delete();
                            $count++;
                        }
                    }
                }
            break;

            case "denied":
                foreach (ContentNotifierQueue::get() as $q) {
                    if ($rec = $q->getRecord()) {
                        if (!$rec->ContentNotifierApproved) {
                            $q->delete();
                            $count++;
                        }
                    }
                }
            break;


            case "orphaned":
                foreach (ContentNotifierQueue::get() as $q) {
                    if (!$q->getRecord()) {
                        $q->delete();
                        $count++;
                    }
                }
            break;

            default:
                die("Please specify an 'action' parameter ('all','approved','denied', or 'orphaned') in the request");
            break;

        }

        die("Deleted $count records from the content notifier queue.");
    }
}
