<?php

namespace TheWebmen\Articles\Controllers;

use SilverStripe\ORM\DataList;
use SilverStripe\ORM\PaginatedList;
use TheWebmen\Articles\ArticleFilterForm;
use TheWebmen\Articles\Pages\ArticlePage;
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

        if ($this->articles) {
            $this->applyFilters();
            $this->applySorting();
        }

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

        if ($themes) {
            $filterService->applyThemesFilter(explode(',', $themes));
        }

        if ($type) {
            $filterService->applyTypeFilter(explode(',', $type));
        }

        if ($tag) {
            $filterService->applyTagFilter(explode(',', $tag));
        }

        $this->articles = $filterService->getArticles();
    }

    private function applySorting(): void
    {
        $this->articles = $this->articles->sort(
            [
                'Pinned' => 'DESC',
                'Highlighted' => 'DESC',
                'PublicationDate' => 'DESC'
            ]
        );
    }
}
