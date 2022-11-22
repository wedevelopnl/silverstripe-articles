<?php

namespace WeDevelop\Articles\Controllers;

use SilverStripe\ORM\DataList;
use SilverStripe\ORM\PaginatedList;
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

    public function getTypes(): ?DataList
    {
        return $this->data()->getParent()->getTypes();
    }

    protected function getArticleDataList(): ?DataList
    {
        $articles = $this->data()->getManyManyComponents('Articles');

        $this->extend('updateArticleDataList', $articles);

        return $articles;
    }
}
