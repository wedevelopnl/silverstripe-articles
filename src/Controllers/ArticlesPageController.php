<?php

namespace TheWebmen\Articles\Pages;

use App\Forms\ArticleFilterForm;
use SilverStripe\Forms\CheckboxSetField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FormAction;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\PaginatedList;
use TheWebmen\Articles\Filters\TagFilter;
use TheWebmen\Articles\Filters\ThemeFilter;
use TheWebmen\Articles\Filters\TypeFilter;

class ArticlesPageController extends \PageController
{
    /***
     * @var DataList
     */
    private $articles;

    /***
     * @return ArrayList|DataList
     */
    public function getThemes() {
        return $this->data()->hasMethod('getThemes') ? $this->data()->getThemes() : new ArrayList();
    }

    /***
     * @return DataList
     */
    public function getTypes() {
        return $this->data()->Types();
    }

    /***
     * @return Form
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

        if ($this->hasMethod('updatePaginatedArticles')) {
            $pagination = $this->updatePaginatedArticles($pagination);
        }

        $startQueryParam = $this->getRequest()->getVar('start');
        $start = $startQueryParam > 0 ? $startQueryParam : 0;

        if ($start === 0) {
            $pagination->setPageLength($this->PageLength);
        }

        if ($this->getRequest()->isAjax()) {
            return $pagination;
        }

        $pagination->setPageLength($start + $this->PageLength);

        if (!$this->HasStartQueryParam()) {
            $pagination->setPageLength($pagination->getPageLength());
        }

        $pagination->setCurrentPage(1);

        return $pagination;
    }

    /***
     * @return bool
     */
    public function HasStartQueryParam()
    {
        return $this->getRequest()->getVar('start') !== null;
    }

    private function applyThemesFilter()
    {
        $themeFilter = new ThemeFilter();
        $this->articles = $themeFilter->apply($this->getRequest(), $this->articles);
    }

    private function applyTagFilter()
    {
        $tagsFilter = new TagFilter();
        $this->articles = $tagsFilter->apply($this->getRequest(), $this->articles);
    }

    private function applyTypeFilter()
    {
        $typeFilter = new TypeFilter();
        $this->articles = $typeFilter->apply($this->getRequest(), $this->articles);
    }
}
