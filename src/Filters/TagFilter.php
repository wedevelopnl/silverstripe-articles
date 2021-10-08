<?php

namespace TheWebmen\Articles\Filters;

use SilverStripe\Control\HTTPRequest;
use SilverStripe\ORM\DataList;
use TheWebmen\Articles\Models\Tag;
use TheWebmen\Articles\Models\Type;

final class TagFilter
{
    public function apply(HTTPRequest $request, DataList $dataList): DataList
    {
        $tag = $this->getActiveTag($request);

        if (!$tag) {
            return $dataList;
        }

        return $dataList->filter('Tags.ID', $tag->ID);
    }

    /***
     * @param HTTPRequest $request
     * @return Type
     */
    public function getActiveTag(HTTPRequest $request)
    {
        $tag = $request->getVar('tag');
        return Tag::get()->filter('Slug', $tag)->first();
    }
}
