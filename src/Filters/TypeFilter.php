<?php

namespace TheWebmen\Articles\Filters;

use SilverStripe\Control\HTTPRequest;
use SilverStripe\ORM\DataList;
use TheWebmen\Articles\Interfaces\FilterInterface;
use TheWebmen\Articles\Models\Type;

final class TypeFilter implements FilterInterface
{
    public function apply(HTTPRequest $request, DataList $dataList): DataList
    {
        $type = $this->getActiveItems($request);

        if (!$type) {
            return $dataList;
        }

        return $dataList->filter('Type.ID', $type->ID);
    }

    public function getActiveItems(HTTPRequest $request): ?Type
    {
        $type = $request->getVar('type');
        return Type::get()->filter('Slug', $type)->first();
    }
}
