<?php

namespace TheWebmen\Articles\Pages;

use SilverStripe\Forms\FieldList;
use TheWebmen\Articles\Controllers\ArticleThemePageController;

class ArticleThemePage extends \Page
{
    /***
     * @var string
     */
    private static $table_name = 'TheWebmen_ArticleThemePage';

    /***
     * @var string
     */
    private static $singular_name = 'Theme page';

    /***
     * @var string
     */
    private static $plural_name = 'Theme pages';

    /***
     * @var string
     */
    private static $icon_class = 'font-icon-circle-star';

    /***
     * @var array
     */
    private static $allowed_children = [];

    /**
     * @var array
     */
    private static $db = [];

    /***
     * @var array
     */
    private static $has_one = [
        'ArticlesPage' => ArticlesPage::class
    ];

    /***
     * @var array
     */
    private static $many_many = [
        'ArticlePages' => ArticlePage::class,
    ];

    /***
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        return $fields;
    }

    public function getControllerName()
    {
        return ArticleThemePageController::class;
    }
}
