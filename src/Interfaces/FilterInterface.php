<?php

namespace TheWebmen\Articles\Interfaces;

use SilverStripe\Control\HTTPRequest;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\DataObject;

interface FilterInterface
{
    public function apply(array $items, DataList $dataList): DataList;

    /**
     * @return DataList|DataObject
     */
    public function getActiveItems(array $items);
}
