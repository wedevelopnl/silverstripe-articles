<?php

namespace WeDevelop\Articles\Controllers;

use SilverStripe\ORM\DataList;
use SilverStripe\ORM\PaginatedList;
use WeDevelop\Articles\ArticleFilterForm;
use WeDevelop\Articles\Pages\ArticlePage;
use WeDevelop\Articles\Pages\ArticlesPage;
use WeDevelop\Articles\Services\ArticleFilterService;

/**
 * Class ArticlesPageController
 * @package WeDevelop\Articles\Controllers
 *
 * @method ArticlesPage data()
 */
class ArticlesPageController extends \PageController
{
    /**
     * @var DataList
     */
    protected $articles;

    public function getThemes(): ?DataList
    {
        return $this->data()->hasMethod('getThemes') ? $this->data()->getThemes() : null;
    }

    public function getTypes(): ?DataList
    {
        return $this->data()->hasMethod('getTypes') ? $this->data()->getTypes() : null;
    }

    public function ArticleFilterForm(): ArticleFilterForm
    {
        return new ArticleFilterForm($this);
    }

    public function index()
    {
        return $this;
    }

    protected function getArticleDataList(): ?DataList
    {
        $articles = ArticlePage::get()->filter('ParentID', $this->data()->ID);

        $this->extend('updateArticleDataList', $articles);

        return $articles;
    }

    public function init(): ?DataList
    {
        parent::init();

        $this->articles = $this->getArticleDataList();

        if ($this->articles) {
            $this->applyFilters();
        }

        $this->extend('onAfterInit', $this);

        return $this->articles;
    }

    public function PaginatedArticles(): ?PaginatedList
    {
        if ($this->data() instanceof ArticlesPage) {
            $pageLength = $this->data()->PageLength;
        } else {
            $pageLength = $this->data()->Parent()->PageLength;
        }

        $pagination = PaginatedList::create($this->articles, $this->getRequest());
        $pagination->setPageLength($pageLength);
        $pagination->setPaginationGetVar('p');

        $this->extend('updatePaginatedArticles', $pagination);

        return $pagination;
    }

    public function getArticles(): ?DataList
    {
        return $this->articles;
    }

    private function applyFilters(): void
    {
        $URLFilters = $this->getFiltersFromURL();
        $filterService = new ArticleFilterService($this->articles);

        if ($URLFilters['themes']) {
            $filterService->applyThemesFilter(explode(',', $URLFilters['themes']));
        }

        if ($URLFilters['type']) {
            $filterService->applyTypeFilter(explode(',', $URLFilters['type']));
        }

        if ($URLFilters['tag']) {
            $filterService->applyTagFilter(explode(',', $URLFilters['tag']));
        }

        $this->articles = $filterService->getArticles();
    }

    public function hasActiveFilters(): bool
    {
        $URLFilters = $this->getFiltersFromURL();
        return $URLFilters['themes'] || $URLFilters['type'] || $URLFilters['tag'];
    }

    public function getFiltersFromURL(): array
    {
        return [
            'themes' => $this->getRequest()->getVar('thema'),
            'type' => $this->getRequest()->getVar('type'),
            'tag' => $this->getRequest()->getVar('tag'),
        ];
    }
}
