<?php

namespace TheWebmen\Articles\Filters;

use SilverStripe\Control\HTTPRequest;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\SS_List;
use TheWebmen\Articles\Models\Type;

final class TypeFilter
{
    public function apply(HTTPRequest $request, DataList $dataList): DataList
    {
        $type = $this->getActiveType($request);

        if (!$type) {
            return $dataList;
        }

        return $dataList->filter('Type.ID', $type->ID);
    }

    /***
     * @param HTTPRequest $request
     * @return Type
     */
    public function getActiveType(HTTPRequest $request)
    {
        $type = $request->getVar('type');
        return Type::get()->filter('Slug', $type)->first();

    }
}
