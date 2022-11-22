<?php

namespace WeDevelop\Articles\Pages;

use WeDevelop\Articles\ElementalGrid\ElementArticles;

class DeprecatedArticleThemePage extends \Page
{
    /**
     * @var string
     */
    private static $table_name = 'TheWebmen_ArticleThemePage';

    /**
     * @var array
     */
    private static $db = [];

    /**
     * @var array
     */
    private static $has_one = [
        'ArticlesPage' => DeprecatedArticlesPage::class,
    ];

    /**
     * @var array
     */
    private static $many_many = [
        'Articles' => DeprecatedArticlePage::class,
        'ElementArticles' => ElementArticles::class,
    ];
}
