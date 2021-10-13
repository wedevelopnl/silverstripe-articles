<?php

namespace TheWebmen\Articles\Filters;

use SilverStripe\Control\HTTPRequest;
use SilverStripe\ORM\DataList;
use TheWebmen\Articles\Interfaces\FilterInterface;
use TheWebmen\Articles\Models\Type;

final class TypeFilter implements FilterInterface
{
    /***
     * @param HTTPRequest $request
     * @param DataList $dataList
     * @return DataList
     */
    public function apply($request, $dataList)
    {
        $type = $this->getActiveItems($request);

        if (!$type) {
            return $dataList;
        }

        return $dataList->filter('Type.ID', $type->ID);
    }

    /***
     * @param HTTPRequest $request
     * @return Type
     */
    public function getActiveItems($request)
    {
        $type = $request->getVar('type');
        return Type::get()->filter('Slug', $type)->first();

    }
}
