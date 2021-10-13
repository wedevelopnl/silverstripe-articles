<?php

namespace TheWebmen\Articles\Filters;

use SilverStripe\Control\HTTPRequest;
use SilverStripe\ORM\DataList;
use TheWebmen\Articles\Interfaces\FilterInterface;
use TheWebmen\Articles\Models\Tag;

final class TagFilter implements FilterInterface
{
    /***
     * @param HTTPRequest $request
     * @param DataList $dataList
     * @return DataList
     */
    public function apply($request, $dataList)
    {
        $tag = $this->getActiveItems($request);

        if (!$tag) {
            return $dataList;
        }

        return $dataList->filter('Tags.ID', $tag->ID);
    }

    /***
     * @param HTTPRequest $request
     * @return Tag
     */
    public function getActiveItems($request)
    {
        $tag = $request->getVar('tag');
        return Tag::get()->filter('Slug', $tag)->first();
    }
}
