<?php

namespace WeDevelop\Articles\Filters;

use SilverStripe\ORM\DataList;
use WeDevelop\Articles\Interfaces\FilterInterface;
use WeDevelop\Articles\Pages\ArticleThemePage;

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
