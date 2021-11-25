<?php

namespace TheWebmen\Articles\Pages;

use SilverStripe\Control\Controller;
use TheWebmen\Articles\Controllers\ArticlesPageController;
use TheWebmen\Articles\Controllers\ArticleThemePageController;
use TheWebmen\Articles\ElementalGrid\ElementArticles;

class ArticleThemePage extends \Page
{
    /**
     * @var string
     */
    private static $table_name = 'TheWebmen_ArticleThemePage';

    /**
     * @var string
     */
    private static $singular_name = 'Theme page';

    /**
     * @var string
     */
    private static $plural_name = 'Theme pages';

    /**
     * @var string
     */
    private static $description = 'A page that will display articles that are related to a theme';

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
    private static $many_many = [
        'Articles' => ArticlePage::class,
        'ElementArticles' => ElementArticles::class,
    ];

    public function getControllerName(): string
    {
        return ArticleThemePageController::class;
    }


    public function IsActive(): bool
    {
        /** @var ArticleThemePage|ArticlesPageController $controller */
        $controller = Controller::curr();
        $URLFilters = $controller->getFiltersFromURL();
        $themes = $URLFilters['themes'];

        if (in_array($this->data()->URLSegment, explode(',', $themes))) {
            return true;
        }

        return false;
    }
}
