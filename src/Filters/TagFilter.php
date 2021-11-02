<?php

namespace TheWebmen\Articles\Filters;

use SilverStripe\Control\HTTPRequest;
use SilverStripe\ORM\DataList;
use TheWebmen\Articles\Interfaces\FilterInterface;
use TheWebmen\Articles\Models\Tag;

final class TagFilter implements FilterInterface
{
    public function apply(HTTPRequest $request, DataList $dataList): DataList
    {
        $tag = $this->getActiveItems($request);

        if (!$tag) {
            return $dataList;
        }

        return $dataList->filter('Tags.ID', $tag->ID);
    }

    public function getActiveItems(HTTPRequest $request): ?Tag
    {
        $tag = $request->getVar('tag');
        return Tag::get()->filter('Slug', $tag)->first();
    }
}
