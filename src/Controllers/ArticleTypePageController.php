<?php

namespace TheWebmen\Articles\Controllers;

use SilverStripe\ORM\DataList;
use TheWebmen\Articles\Pages\ArticleTypePage;

/**
 * Class ArticleTypePageController
 * @package TheWebmen\Articles\Controllers
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
