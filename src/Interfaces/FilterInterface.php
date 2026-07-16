<?php

namespace WeDevelop\Articles\Interfaces;

use SilverStripe\ORM\DataObject;
use SilverStripe\Model\List\SS_List;

interface FilterInterface
{
    public function apply(array $items, SS_List $dataList): SS_List;

    /**
     * @return SS_List|DataObject
     */
    public function getActiveItems(array $items);
}
