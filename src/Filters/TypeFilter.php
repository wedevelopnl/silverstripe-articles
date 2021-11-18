<?php

namespace TheWebmen\Articles\Filters;

use SilverStripe\ORM\DataList;
use TheWebmen\Articles\Interfaces\FilterInterface;
use TheWebmen\Articles\Models\Type;

final class TypeFilter implements FilterInterface
{
    public function apply(array $items, DataList $dataList): DataList
    {
        $type = $this->getActiveItems($items);

        if (!$type) {
            return $dataList;
        }

        return $dataList->filter('Type.ID', $type->column('ID'));
    }

    public function getActiveItems(array $items)
    {
        return Type::get()->filter('Slug', $items);
    }
}
