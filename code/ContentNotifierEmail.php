<?php


class ContentNotifierEmail extends Object
{

    protected $emailer;


    protected $records;


    public function __construct() {        
        $this->emailer = Email::create();        
        $config = $this->config();

        $this->emailer->setFrom($config->from);
        $this->emailer->setTo($config->to);
        $this->emailer->setSubject($config->subject);
        $this->emailer->setTemplate($config->template);
    }


    public function setRecords(DataList $list) {
        $this->records = $list;

        return $this;
    }


    public function send() {
        if(!$this->records) {
            $this->setRecords(ContentNotifierQueue::get_unnotified());
        }

        ContentNotifierExtension::disable_filtering();

        $total = $this->records->count();
        $grouped = GroupedList::create(
            $this->records->limit($this->config()->items_limit)
        )->GroupedBy('Category');

        $this->emailer->populateTemplate(array(
            'Headline' => $this->config()->headline,
            'GroupedItems' => $grouped,
            'Total' => $total,            
            'Link' => Controller::join_links(
                Director::absoluteBaseURL(),
                'admin',
                'content-notifications'
            )
        ));

        $this->emailer->send();

        foreach($this->records as $record) {
            $record->HasNotified = true;
            $record->write();
        }

        ContentNotifierExtension::enable_filtering(true);
    }


}