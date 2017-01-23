<?php

namespace SilverStripe\ContentNotifier;

interface ContentNotifier
{
    public function getContentNotifierExcerpt();

    public function getContentNotifierLink();

    public function getContentNotifierHeadLine();
}
