<?php

declare(strict_types=1);

namespace WeDevelop\Articles\Services;

use SilverStripe\ORM\SS_List;
use WeDevelop\Articles\Filters\TagFilter;
use WeDevelop\Articles\Filters\ThemeFilter;
use WeDevelop\Articles\Filters\TypeFilter;

final class ArticleFilterService
{
    /**
     * @var SS_List
     */
    private $articles;

    public function __construct(SS_List $articles)
    {
        $this->articles = $articles;
    }

    public function applyThemesFilter(array $themes): void
    {
        $themeFilter = new ThemeFilter();
        $this->articles = $themeFilter->apply($themes, $this->articles);
    }

    public function applyTagFilter(array $tags): void
    {
        $tagsFilter = new TagFilter();
        $this->articles = $tagsFilter->apply($tags, $this->articles);
    }

    public function applyTypeFilter(array $types): void
    {
        $typeFilter = new TypeFilter();
        $this->articles = $typeFilter->apply($types, $this->articles);
    }

    public function getArticles(): SS_List
    {
        return $this->articles;
    }
}
