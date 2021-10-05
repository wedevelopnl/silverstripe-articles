<?php

namespace Webmen\Articles\Pages;

use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\Forms\GridField\GridFieldAddExistingAutocompleter;
use SilverStripe\Forms\LiteralField;
use SilverStripe\ORM\FieldType\DBDatetime;
use SilverStripe\ORM\ManyManyList;
use SilverStripe\Security\Member;
use SilverStripe\Forms\DateField;
use SilverStripe\Versioned\GridFieldArchiveAction;

class ArticlePage extends \Page
{
    /***
     * @var string
     */
    private static $table_name = 'Webmen_ArticlePage';

    /***
     * @var string
     */
    private static $singular_name = 'Article page';

    /***
     * @var string
     */
    private static $plural_name = 'Articles page';

    /***
     * @var string
     */
    private static $icon_class = 'font-icon-p-article';

    /***
     * @var bool
     */
    private static $show_in_sitetree = false;

    /***
     * @var array
     */
    private static $allowed_children = [];

    /**
     * @var array
     */
    private static $db = [
        'Date' => 'Date'
    ];

    /***
     * @var string
     */
    private static $default_sort = 'Date DESC';

    /***
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        return $fields;
    }
}
