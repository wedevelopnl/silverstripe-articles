<?php

namespace TheWebmen\Articles\Controllers;

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
use TheWebmen\Articles\Forms\ArticleCheckboxSetField;

class ArticlesPageController extends \PageController
{
    /***
     * @var DataList
     */
    protected $articles;

    /***
     * @return ArrayList|DataList
     */
    protected function getThemes() {
        return $this->data()->hasMethod('getThemes') ? $this->data()->getThemes() : new ArrayList();
    }

    /***
     * @return DataList
     */
    protected function getTypes() {
        return $this->data()->Types();
    }

    /***
     * @return Form
     */
    public function ArticleFilterForm()
    {
        $fields = new FieldList(
            ArticleCheckboxSetField::create(
                'thema',
                'Thema\'s',
                $this->getThemes()->map('URLSegment', 'Title')->toArray()
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
        $form->setAttribute('enctype', '');
        $form->setFormMethod('GET');
        $form->disableSecurityToken();
        $form->loadDataFrom($this->getRequest()->getVars());

        return $form;
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

    /***
     * @return bool
     */
    public function HasStartQueryParam()
    {
        return $this->getRequest()->getVar('start') !== null;
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
