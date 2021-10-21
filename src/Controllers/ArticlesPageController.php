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

/**
 * Class ArticlesPageController
 * @package TheWebmen\Articles\Controllers
 */
class ArticlesPageController extends \PageController
{
    /***
     * @var DataList
     */
    protected $articles;

    /***
     * @return ArrayList|DataList
     */
    public function getThemes()
    {
        return $this->data()->hasMethod('getThemes') ? $this->data()->getThemes() : new ArrayList();
    }

    /***
     * @return DataList
     */
    public function getTypes()
    {
        return $this->data()->Types();
    }

    /***
     * @return ArticleFilterForm
     */
    public function ArticleFilterForm()
    {
        return new ArticleFilterForm($this);
    }

    /***
     * @return $this
     */
    public function index()
    {
        return $this;
    }

    /***
     * @return DataList
     */
    protected function getArticleDataList()
    {
        return ArticlePage::get()->filter('ParentID', $this->data()->ID);
    }

    /***
     * @return DataList
     */
    public function init()
    {
        parent::init();

        $this->articles = $this->getArticleDataList();

        if ($this->hasMethod('updateArticles')) {
            $this->articles = $this->updateArticles($articles);
        }

        $this->applyThemesFilter();
        $this->applyTypeFilter();
        $this->applyTagFilter();

        return $this->articles;
    }

    /***
     * @return PaginatedList
     */
    public function PaginatedArticles()
    {
        $pagination = PaginatedList::create($this->articles, $this->getRequest());
        $pagination->setPageLength($this->PageLength);
        $pagination->setPaginationGetVar('p');

        if ($this->hasMethod('updatePaginatedArticles')) {
            $pagination = $this->updatePaginatedArticles($pagination);
        }

        return $pagination;
    }

    protected function applyThemesFilter()
    {
        $themeFilter = new ThemeFilter();
        $this->articles = $themeFilter->apply($this->getRequest(), $this->articles);
    }

    protected function applyTagFilter()
    {
        $tagsFilter = new TagFilter();
        $this->articles = $tagsFilter->apply($this->getRequest(), $this->articles);
    }

    protected function applyTypeFilter()
    {
        $typeFilter = new TypeFilter();
        $this->articles = $typeFilter->apply($this->getRequest(), $this->articles);
    }
}
