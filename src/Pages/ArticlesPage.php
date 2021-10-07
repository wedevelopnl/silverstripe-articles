<?php

namespace Webmen\Articles\Pages;


use Restruct\Silverstripe\SiteTreeButtons\GridFieldAddNewSiteTreeItemButton;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\Forms\NumericField;
use SilverStripe\Lumberjack\Forms\GridFieldConfig_Lumberjack;
use SilverStripe\Lumberjack\Forms\GridFieldSiteTreeAddNewButton;
use SilverStripe\ORM\DataList;
use Webmen\Articles\Models\ArticleTag;
use Webmen\Articles\Models\ArticleType;

/**
 * Class ArticlesPage
 * @package Webmen\Articles\Pages
 *
 * @property int $PageLength
 */
class ArticlesPage extends \Page
{

    /***
     * @var string
     */
    private static $table_name = 'Webmen_ArticlesPage';

    /***
     * @var string
     */
    private static $singular_name = 'Articles overview page';

    /***
     * @var string
     */
    private static $plural_name = 'Articles overview pages';

    /***
     * @var string
     */
    private static $icon_class = 'font-icon-p-article';

    /**
     * @var array
     */
    private static $allowed_children = [
        ArticlePage::class,
        ArticleThemePage::class,
    ];

    private static $default_child = ArticlePage::class;

    /**
     * @var array
     */
    private static $db = [
        'PageLength' => 'Int'
    ];

    /**
     * @var array
     */
    private static $has_many = [
        'Types' => ArticleType::class,
        'Tags' => ArticleTag::class,
    ];

    /**
     * @return \SilverStripe\Forms\FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->addFieldToTab(
            'Root.Themes',
            $this->createGridField(
                'Themes',
                ArticleThemePage::get()->filter('ParentID', $this->owner->ID)
            )
        );

        $fields->addFieldsToTab(
            'Root.Types',
            [
                GridField::create('Types', 'Types', $this->Types(), new GridFieldConfig_RecordEditor()),
            ]
        );
        $fields->addFieldsToTab(
            'Root.Tags',
            [
                GridField::create('Tags', 'Tags', $this->Tags(), new GridFieldConfig_RecordEditor())
            ]
        );

        $fields->replaceField(
            'ChildPages',
            $this->createGridField(
                'Articles',
                ArticlePage::get()->filter('ParentID', $this->owner->ID)
            )
        );

        $fields->insertBefore('Articles', NumericField::create('PageLength'));



        return $fields;
    }

    /**
     * @return string
     */
    public function getLumberjackTitle()
    {
        return _t(self::class . '.ARTICLES', 'Articles');
    }


    /***
     * @param string $type
     * @param DataList $list
     * @return GridField
     */
    private function createGridField($type, $list)
    {
        $config = GridFieldConfig_Lumberjack::create()
            ->removeComponentsByType(GridFieldSiteTreeAddNewButton::class)
            ->addComponent(new GridFieldAddNewSiteTreeItemButton('buttons-before-left'));

        return GridField::create($type, $type, $list, $config);
    }
}
