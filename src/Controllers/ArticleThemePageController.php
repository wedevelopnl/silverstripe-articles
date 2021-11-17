<?php

namespace TheWebmen\Articles\Controllers;

use SilverStripe\Forms\Form;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\ManyManyList;
use TheWebmen\Articles\ArticleFilterForm;
use TheWebmen\Articles\Pages\ArticlesPage;

class ArticleThemePageController extends ArticlesPageController
{
    public function getTypes(): ?DataList
    {
        return $this->data()->getParent()->Types();
    }

    public function getThemes(): ?DataList
    {
        return $this->data()->Parent()->hasMethod('getThemes') ? $this->data()->Parent()->getThemes() : null;
    }

    public function ArticleFilterForm(): ArticleFilterForm
    {
        $form = parent::ArticleFilterForm();

        $field = $form->Fields()->removeByName('thema');

        return $form;
    }

    protected function getArticleDataList(): ?DataList
    {
        return $this->data()->getManyManyComponents('ArticlePages');
    }
}
