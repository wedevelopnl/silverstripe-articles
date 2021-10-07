<?php

namespace Webmen\Articles\Pages;

use SilverStripe\Forms\FieldList;

class ArticleThemePage extends \Page
{
    /***
     * @var string
     */
    private static $table_name = 'Webmen_ThemePage';

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
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        return $fields;
    }
}
