<?php

namespace TheWebmen\Articles\Controllers;

use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FormAction;
use SilverStripe\Forms\OptionsetField;
use TheWebmen\Articles\Filters\ThemeFilter;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\PaginatedList;
use SilverStripe\SiteConfig\SiteConfig;
use TheWebmen\Articles\Filters\TypeFilter;
use TheWebmen\Articles\Models\Type;
use TheWebmen\Articles\Pages\ArticlePage;
use TheWebmen\Articles\Pages\ArticlesPageController;
use TheWebmen\Articles\Pages\ArticleThemePage;
use App\Forms\ArticleFilterForm;

class ArticleThemePageController extends ArticlesPageController
{
    /***
     * @return mixed
     */
    protected function getTypes() {
        return $this->data()->getParent()->Types();
    }

    /***
     * @return ArticleFilterForm
     */
    public function ArticleFilterForm()
    {
        $form = parent::ArticleFilterForm();

        $field = $form->Fields()->removeByName('thema');

        return $form;
    }

    /***
     * @return DataList
     */
    protected function getArticleList()
    {
        return $this->data()->getManyManyComponents('ArticlePages');
    }
}
