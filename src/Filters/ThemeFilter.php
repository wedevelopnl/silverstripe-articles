<?php

namespace TheWebmen\Articles\Filters;

use TheWebmen\Articles\Pages\ArticleThemePage;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\SS_List;

final class ThemeFilter
{
    public function apply(HTTPRequest $request, DataList $dataList): DataList
    {
        $themes = $this->getActiveThemes($request);

        if (count($themes) === 0) {
            return $dataList;
        }

        return $dataList->filter('Themes.ID', $themes->column('ID'));
    }

    public function getActiveThemes(HTTPRequest $request): SS_List
    {
        $themes = $request->getVar('thema');

        if (empty($themes)) {
            return new ArrayList();
        }

        return ArticleThemePage::get()->filter('URLSegment', array_keys($themes));
    }
}
