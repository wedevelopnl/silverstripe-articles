<?php

declare(strict_types=1);

namespace TheWebmen\Articles\Services;

use SilverStripe\ORM\DataList;
use TheWebmen\Articles\Filters\TagFilter;
use TheWebmen\Articles\Filters\ThemeFilter;
use TheWebmen\Articles\Filters\TypeFilter;

final class ArticleFilterService
{
    /**
     * @var DataList
     */
    private $articles;

    public function __construct(DataList $articles)
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

    public function getArticles(): DataList
    {
        return $this->articles;
    }
}
