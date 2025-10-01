<?php

namespace WeDevelop\Articles\Filters;

use SilverStripe\ORM\SS_List;
use WeDevelop\Articles\Interfaces\FilterInterface;
use WeDevelop\Articles\Models\Tag;

final class TagFilter implements FilterInterface
{
    public function apply(array $items, SS_List $dataList): SS_List
    {
        $tag = $this->getActiveItems($items);

        if (!$tag) {
            return $dataList;
        }

        return $dataList->filter('Tags.ID', $tag->ID);
    }

    public function getActiveItems(array $items)
    {
        if (count($items) > 1) {
            return Tag::get()->filter('Slug', $items);
        }

        return Tag::get()->filter('Slug', $items[0])->first();
    }
}
