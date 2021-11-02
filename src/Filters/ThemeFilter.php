<?php

namespace TheWebmen\Articles\Filters;

use SilverStripe\Control\HTTPRequest;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\DataList;
use TheWebmen\Articles\Interfaces\FilterInterface;
use TheWebmen\Articles\Pages\ArticleThemePage;

final class ThemeFilter implements FilterInterface
{
    public function apply(HTTPRequest $request, DataList $dataList): DataList
    {
        $themes = $this->getActiveItems($request);

        if (count($themes) === 0) {
            return $dataList;
        }

        return $dataList->filter('Themes.ID', $themes->column('ID'));
    }

    public function getActiveItems(HTTPRequest $request): DataList
    {
        $themes = $request->getVar('thema');

        if (empty($themes)) {
            return new DataList(ArticleThemePage::class);
        }

        return ArticleThemePage::get()->filter('URLSegment', explode(',', $themes));
    }
}
