<?php

namespace TheWebmen\Articles\Controllers;

use SilverStripe\ORM\DataList;

class ArticleThemePageController extends ArticlesPageController
{
    public function getTypes(): ?DataList
    {
        return $this->data()->getParent()->Types();
    }

    protected function getArticleDataList(): ?DataList
    {
        return $this->data()->getManyManyComponents('ArticlePages');
    }
}
