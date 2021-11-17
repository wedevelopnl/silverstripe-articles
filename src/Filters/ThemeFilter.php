<?php

namespace TheWebmen\Articles\Filters;

use SilverStripe\Control\HTTPRequest;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\DataList;
use TheWebmen\Articles\Interfaces\FilterInterface;
use TheWebmen\Articles\Pages\ArticleThemePage;

final class ThemeFilter implements FilterInterface
{
    public function apply(array $items, DataList $dataList): DataList
    {
        $themes = $this->getActiveItems($items);

        if (count($themes) === 0) {
            return $dataList;
        }

        return $dataList->filter('Themes.ID', $themes->column('ID'));
    }

    public function getActiveItems(array $items): DataList
    {
        if (empty($items)) {
            return new DataList(ArticleThemePage::class);
        }

        return ArticleThemePage::get()->filter('URLSegment', $items);
    }
}
