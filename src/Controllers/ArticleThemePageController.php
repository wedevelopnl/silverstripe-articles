<?php

namespace TheWebmen\Articles\Controllers;

use SilverStripe\Forms\Form;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\ManyManyList;

class ArticleThemePageController extends ArticlesPageController
{
    /***
     * @return DataList
     */
    public function getTypes()
    {
        return $this->data()->getParent()->Types();
    }

    /***
     * @return Form
     */
    public function ArticleFilterForm()
    {
        $form = parent::ArticleFilterForm();

        $field = $form->Fields()->removeByName('thema');

        return $form;
    }

    /***
     * @return ManyManyList
     */
    protected function getArticleDataList()
    {
        return $this->data()->getManyManyComponents('ArticlePages');
    }
}
