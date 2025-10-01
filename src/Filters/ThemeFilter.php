<?php

namespace WeDevelop\Articles\Filters;

use SilverStripe\ORM\DataList;
use SilverStripe\ORM\SS_List;
use WeDevelop\Articles\Interfaces\FilterInterface;
use WeDevelop\Articles\Pages\ArticleThemePage;

final class ThemeFilter implements FilterInterface
{
    public function apply(array $items, SS_List $dataList): SS_List
    {
        $themes = $this->getActiveItems($items);

        if (count($themes) === 0) {
            return $dataList;
        }

        return $dataList->filter('Themes.ID', $themes->column('ID'));
    }

    public function getActiveItems(array $items): SS_List
    {
        if (empty($items)) {
            return new DataList(ArticleThemePage::class);
        }

        return ArticleThemePage::get()->filter('URLSegment', $items);
    }
}
