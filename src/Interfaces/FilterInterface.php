<?php

namespace TheWebmen\Articles\Interfaces;

use SilverStripe\Control\HTTPRequest;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\DataObject;

interface FilterInterface
{
    /***
     * @param HTTPRequest $request
     * @param DataList $dataList
     * @return mixed
     */
    public function apply($request, $dataList);

    /***
     * @param $request
     * @return ArrayList|DataList|DataObject
     */
    public function getActiveItems($request);
}
