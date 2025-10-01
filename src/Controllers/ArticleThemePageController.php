<?php

namespace WeDevelop\Articles\Controllers;

use SilverStripe\ORM\SS_List;
use WeDevelop\Articles\Pages\ArticleThemePage;

/**
 * Class ArticleThemePageController
 * @package WeDevelop\Articles\Controllers
 *
 * @method ArticleThemePage data()
 */
class ArticleThemePageController extends ArticlesPageController
{
    public function index()
    {
        return $this;
    }

    public function getTypes(): ?SS_List
    {
        return $this->data()->getParent()->getTypes();
    }

    protected function getArticleDataList(): ?SS_List
    {
        $articles = $this->data()->getManyManyComponents('Articles');

        $this->extend('updateArticleDataList', $articles);

        return $articles;
    }
}
