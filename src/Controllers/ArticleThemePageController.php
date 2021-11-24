<?php

namespace TheWebmen\Articles\Controllers;

use SilverStripe\ORM\DataList;
use TheWebmen\Articles\Pages\ArticleThemePage;

/**
 * Class ArticleThemePageController
 * @package TheWebmen\Articles\Controllers
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
        return $this->data()->getManyManyComponents('Articles');
    }
}
