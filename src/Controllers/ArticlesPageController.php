<?php

namespace TheWebmen\Articles\Controllers;

use SilverStripe\Forms\Form;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\PaginatedList;
use TheWebmen\Articles\ArticleFilterForm;
use TheWebmen\Articles\Filters\TagFilter;
use TheWebmen\Articles\Filters\ThemeFilter;
use TheWebmen\Articles\Filters\TypeFilter;
use TheWebmen\Articles\Pages\ArticlePage;
use TheWebmen\Articles\Pages\ArticlesPage;
use TheWebmen\Articles\Pages\ArticleThemePage;
use TheWebmen\Articles\Services\ArticleFilterService;

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
        return $this->data()->Types();
    }

    public function ArticleFilterForm(): ArticleFilterForm
    {
        return new ArticleFilterForm($this);
    }

    public function index(): self
    {
        return $this;
    }

    protected function getArticleDataList(): ?DataList
    {
        return ArticlePage::get()->filter('ParentID', $this->data()->ID);
    }

    public function init(): ?DataList
    {
        parent::init();

        $this->articles = $this->getArticleDataList();

        $this->applyFilters();

        return $this->articles;
    }

    public function PaginatedArticles(): ?PaginatedList
    {
        $pagination = PaginatedList::create($this->articles, $this->getRequest());
        $pagination->setPageLength($this->PageLength);
        $pagination->setPaginationGetVar('p');

        if ($this->hasMethod('updatePaginatedArticles')) {
            $pagination = $this->updatePaginatedArticles($pagination);
        }

        return $pagination;
    }

    private function applyFilters(): void
    {
        $themes = $this->getRequest()->getVar('thema');
        $type = $this->getRequest()->getVar('type');
        $tag = $this->getRequest()->getVar('tag');

        $filterService = new ArticleFilterService($this->articles);
        $filterService->applyThemesFilter(explode(',', $themes));
        $filterService->applyTypeFilter(explode(',', $type));
        $filterService->applyTagFilter(explode(',', $tag));
        $this->articles = $filterService->getArticles();
    }
}
