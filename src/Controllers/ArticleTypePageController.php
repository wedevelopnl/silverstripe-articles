<?php

namespace WeDevelop\Articles\Controllers;

use SilverStripe\ORM\DataList;
use WeDevelop\Articles\Pages\ArticleTypePage;

/**
 * Class ArticleTypePageController
 * @package WeDevelop\Articles\Controllers
 *
 * @method ArticleTypePage data()
 */
class ArticleTypePageController extends ArticlesPageController
{
    public function index()
    {
        return $this;
    }

    public function getThemes(): ?DataList
    {
        return $this->data()->getParent()->getThemes();
    }

    protected function getArticleDataList(): ?DataList
    {
        $articles = $this->data()->getComponents('Articles');

        $this->extend('updateArticleDataList', $articles);

        return $articles;
    }
}
