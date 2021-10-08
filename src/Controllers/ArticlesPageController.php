<?php

namespace TheWebmen\Articles\Pages;

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

    protected function getThemes() {
        return $this->data()->hasMethod('getThemes') ? $this->data()->getThemes() : new ArrayList();
    }

    protected function getTypes() {
        return $this->data()->Types();
    }

    /***
     * @return ArticleFilterForm
     */
    public function ArticleFilterForm()
    {
        $fields = new FieldList(
            CheckboxSetField::create(
                'thema',
                'Thema\'s',
                $this->getThemes()->map('URLSegment')->toArray()
            ),
            DropdownField::create(
                'type',
                'Type',
                $this->getTypes()->map('Slug', 'Title')->toArray()
            )->setHasEmptyDefault(true)->setEmptyString('Choose a type')
        );

        $actions = new FieldList(
            FormAction::create('doArticleFilterForm', 'Filter')
                ->setName('')
        );

        $form = new Form($this, '', $fields, $actions);
        $form->setFormMethod('GET');

        $form->disableSecurityToken();
        $form->loadDataFrom($this->getRequest()->getVars());

        return $form;
    }

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

    public function init()
    {
        parent::init();

        $this->articles = $this->getArticleDataList();

        if ($this->hasMethod('updateArticles')) {
            $this->articles = $this->updateArticles($articles);
        }

        $this->applyThemesFilter();
        var_dump($this->articles->count());
        $this->applyTypeFilter();        var_dump($this->articles->count());

        $this->applyTagFilter();        var_dump($this->articles->count());


        return $this->articles;
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

    public function HasStartQueryParam()
    {
        return $this->getRequest()->getVar('start') !== null;
    }
}
