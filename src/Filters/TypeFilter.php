<?php

namespace WeDevelop\Articles\Filters;

use SilverStripe\ORM\DataList;
use SilverStripe\ORM\SS_List;
use WeDevelop\Articles\Interfaces\FilterInterface;
use WeDevelop\Articles\Pages\ArticleTypePage;

final class TypeFilter implements FilterInterface
{
    public function apply(array $items, SS_List $dataList): SS_List
    {
        $types = $this->getActiveItems($items);

        if (count($types) === 0) {
            return $dataList;
        }

        return $dataList->filter('Type.ID', $types->column('ID'));
    }

    public function getActiveItems(array $items): SS_List
    {
        if (empty($items)) {
            return new DataList(ArticleTypePage::class);
        }

        return ArticleTypePage::get()->filter('URLSegment', $items);
    }
}
