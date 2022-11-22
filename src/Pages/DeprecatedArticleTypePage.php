<?php

namespace WeDevelop\Articles\Pages;

use WeDevelop\Articles\ElementalGrid\ElementArticles;

class DeprecatedArticleTypePage extends \Page
{
    /**
     * @var string
     */
    private static $table_name = 'TheWebmen_ArticleTypePage';

    /**
     * @var array
     */
    private static $allowed_children = [];

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
    private static $has_many = [
        'Articles' => DeprecatedArticlePage::class,
    ];

    /**
     * @var array
     */
    private static $many_many = [
        'Articles' => DeprecatedArticlePage::class,
        'ElementArticles' => ElementArticles::class,
    ];
}
