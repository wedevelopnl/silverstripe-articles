<?php

namespace TheWebmen\Articles\Filters;

use SilverStripe\Control\HTTPRequest;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\DataList;
use TheWebmen\Articles\Interfaces\FilterInterface;
use TheWebmen\Articles\Pages\ArticleThemePage;

final class ThemeFilter implements FilterInterface
{
    /***
     * @param HTTPRequest $request
     * @param DataList $dataList
     * @return DataList
     */
    public function apply($request, $dataList)
    {
        $themes = $this->getActiveItems($request);

        if (count($themes) === 0) {
            return $dataList;
        }

        return $dataList->filter('Themes.ID', $themes->column('ID'));
    }

    /***
     * @param $request
     * @return ArrayList|DataList
     */
    public function getActiveItems($request)
    {
        $themes = $request->getVar('thema');

        if (empty($themes)) {
            return new ArrayList();
        }

        return ArticleThemePage::get()->filter('URLSegment', array_values($themes));
    }
}
