<?php

namespace TheWebmen\Articles\Filters;

use SilverStripe\ORM\DataList;
use TheWebmen\Articles\Interfaces\FilterInterface;
use TheWebmen\Articles\Pages\ArticleTypePage;

final class TypeFilter implements FilterInterface
{
    public function apply(array $items, DataList $dataList): DataList
    {
        $types = $this->getActiveItems($items);

        if (count($types) === 0) {
            return $dataList;
        }

        return $dataList->filter('Type.ID', $types->column('ID'));
    }

    public function getActiveItems(array $items): DataList
    {
        if (empty($items)) {
            return new DataList(ArticleTypePage::class);
        }

        return ArticleTypePage::get()->filter('URLSegment', $items);
    }
}
