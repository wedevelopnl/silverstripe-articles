<?php

namespace TheWebmen\Articles\Pages;

use TheWebmen\Articles\Controllers\ArticleTypePageController;
use TheWebmen\Articles\ElementalGrid\ElementArticles;

class ArticleTypePage extends \Page
{
    /**
     * @var string
     */
    private static $table_name = 'TheWebmen_ArticleTypePage';

    /**
     * @var string
     */
    private static $singular_name = 'Type page';

    /**
     * @var string
     */
    private static $plural_name = 'Type pages';

    /**
     * @var string
     */
    private static $description = 'A page that will display articles that are related to a type';

    /**
     * @var string
     */
    private static $icon_class = 'font-icon-circle-star';

    /**
     * @var bool
     */
    private static $can_be_root = false;

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
        'ArticlesPage' => ArticlesPage::class
    ];

    /**
     * @var array
     */
    private static $has_many = [
        'Articles' => ArticlePage::class,
    ];

    /**
     * @var array
     */
    private static $many_many = [
        'Articles' => ArticlePage::class,
        'ElementArticles' => ElementArticles::class,
    ];

    public function getControllerName(): string
    {
        return ArticleTypePageController::class;
    }
}
