<?php

namespace TheWebmen\Articles\Pages;

use Restruct\Silverstripe\SiteTreeButtons\GridFieldAddNewSiteTreeItemButton;
use SilverStripe\Control\Controller;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\Forms\NumericField;
use SilverStripe\Lumberjack\Forms\GridFieldConfig_Lumberjack;
use SilverStripe\Lumberjack\Forms\GridFieldSiteTreeAddNewButton;
use SilverStripe\ORM\DataList;
use TheWebmen\Articles\Controllers\ArticlesPageController;
use TheWebmen\Articles\Models\Tag;
use TheWebmen\Articles\Models\Type;

/**
 * Class ArticlesPage
 * @package TheWebmen\Articles\Pages
 *
 * @property int $PageLength
 */
class ArticlesPage extends \Page
{
    /***
     * @var string
     */
    private static $table_name = 'TheWebmen_ArticlesPage';

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
    private static $defaults = [
        'PageLength' => 10
    ];

    /**
     * @var array
     */
    private static $has_many = [
        'Types' => Type::class,
        'Tags' => Tag::class,
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
                _t('Theme.Plural', 'Themes'),
                ArticleThemePage::get()->filter('ParentID', $this->owner->ID)
            )
        );

        $fields->addFieldsToTab(
            'Root.Types',
            [
                GridField::create(
                    'Types',
                    _t('Type.Plural', 'Types'),
                    $this->Types(),
                    new GridFieldConfig_RecordEditor()
                ),
            ]
        );
        $fields->addFieldsToTab(
            'Root.Tags',
            [
                GridField::create('Tags', _t('Tag.Plural', 'Tags'), $this->Tags(), new GridFieldConfig_RecordEditor())
            ]
        );

        $fields->replaceField(
            'ChildPages',
            $this->createGridField(
                'Articles',
                _t(self::class . '.ARTICLES', 'Articles'),
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
    private function createGridField($type, $title, $list)
    {
        $config = GridFieldConfig_Lumberjack::create()
            ->removeComponentsByType(GridFieldSiteTreeAddNewButton::class)
            ->addComponent(new GridFieldAddNewSiteTreeItemButton('buttons-before-left'));

        return GridField::create($type, $title, $list, $config);
    }

    public function getThemes()
    {
        return ArticleThemePage::get()->filter(
            [
                'ParentID' => $this->ID
            ]
        );
    }

    public function getTitle()
    {
        $controller = Controller::curr();
        $activeTagFilter = $controller->getRequest()->getVar('tag');

        if ($activeTagFilter) {
            $tag = Tag::get()->filter('Slug', $activeTagFilter)->first();
        }

        return $tag->Title ?? $this->getField('Title');
    }

    public function getControllerName()
    {
        return ArticlesPageController::class;
    }
}
