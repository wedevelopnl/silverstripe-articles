<?php

namespace TheWebmen\Articles\Pages;

use App\Pages\MealPage;
use Restruct\Silverstripe\SiteTreeButtons\GridFieldAddNewSiteTreeItemButton;
use SilverStripe\Control\Controller;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldAddExistingAutocompleter;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\Forms\NumericField;
use SilverStripe\Lumberjack\Forms\GridFieldConfig_Lumberjack;
use SilverStripe\Lumberjack\Forms\GridFieldSiteTreeAddNewButton;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\HasManyList;
use SilverStripe\ORM\ManyManyList;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use TheWebmen\Articles\Controllers\ArticlesPageController;
use TheWebmen\Articles\Models\Author;
use TheWebmen\Articles\Models\Tag;

/**
 * Class ArticlesPage
 * @package TheWebmen\Articles\Pages
 *
 * @property int $PageLength
 * @method ArticlePage|HasManyList Articles()
 * @method Author Authors()
 * @method ArticlePage|ManyManyList HighlightedArticles()
 * @method ArticlePage|ManyManyList PinnedArticles()
 */
class ArticlesPage extends \Page
{
    /**
     * @var string
     */
    private static $table_name = 'TheWebmen_ArticlesPage';

    /**
     * @var string
     */
    private static $singular_name = 'Articles overview page';

    /**
     * @var string
     */
    private static $plural_name = 'Articles overview pages';

    /**
     * @var string
     */
    private static $icon_class = 'font-icon-p-article';

    /**
     * @var array
     */
    private static $allowed_children = [
        '*'.ArticlePage::class,
        '*'.ArticleThemePage::class,
        '*'.ArticleTypePage::class,
    ];

    /**
     * @var string
     */
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
        'Tags' => Tag::class,
        'Authors' => Author::class,
    ];

    /**
     * @var array
     */
    private static $many_many = [
        'HighlightedArticles' => ArticlePage::class,
        'PinnedArticles' => ArticlePage::class,
    ];

    /**
     * @var array
     */
    private static $many_many_extraFields = [
        'HighlightedArticles' => [
            'HighlightedSort' => 'Int',
        ],
        'PinnedArticles' => [
            'PinnedSort' => 'Int',
        ],
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
                ArticleThemePage::get()->filter('ParentID', $this->ID)
            )
        );

        $fields->addFieldToTab(
            'Root.Types',
            $this->createGridField(
                'Types',
                _t('Type.Plural', 'Types'),
                ArticleTypePage::get()->filter('ParentID', $this->ID)
            )
        );

        $fields->addFieldsToTab(
            'Root.Authors',
            [
                GridField::create(
                    'Authors',
                    _t('Authors.Plural', 'Authors'),
                    $this->Authors(),
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
                ArticlePage::get()->filter('ParentID', $this->ID)
            )
        );

        $fields->addFieldToTab(
            'Root.Highlighted',
            new GridField(
                'HighlightedArticles',
                'Highlighted articles',
                $this->HighlightedArticles(),
                $this->getGridConfig('HighlightedSort')
            )
        );

        $fields->addFieldToTab(
            'Root.Pinned',
            new GridField(
                'PinnedArticles',
                'Pinned articles',
                $this->PinnedArticles(),
                $this->getGridConfig('PinnedSort')
            )
        );

        $fields->insertBefore('Articles', NumericField::create('PageLength'));

        return $fields;
    }

    private function getGridConfig(string $sortColumn): GridFieldConfig_RelationEditor
    {
        $gridfieldConfig = GridFieldConfig_RelationEditor::create();
        $gridfieldConfig->addComponent(new GridFieldOrderableRows($sortColumn));
        $autocompleter = $gridfieldConfig->getComponentByType(GridFieldAddExistingAutocompleter::class);
        $autocompleter
            ->setSearchList(
                ArticlePage::get()->filter(
                    [
                        'ParentID' => $this->ID
                    ]
                )
            );

        return $gridfieldConfig;
    }

    public function getLumberjackTitle(): string
    {
        return _t(self::class . '.ARTICLES', 'Articles');
    }

    private function createGridField(string $type, string $title, DataList $list): GridField
    {
        $config = GridFieldConfig_Lumberjack::create()
            ->removeComponentsByType(GridFieldSiteTreeAddNewButton::class)
            ->addComponent(new GridFieldAddNewSiteTreeItemButton('buttons-before-left'));

        return GridField::create($type, $title, $list, $config);
    }

    public function getThemes(): DataList
    {
        return ArticleThemePage::get()->filter(
            [
                'ParentID' => $this->ID
            ]
        );
    }

    public function getTypes(): DataList
    {
        return ArticleTypePage::get()->filter(
            [
                'ParentID' => $this->ID
            ]
        );
    }

    public function getTitle(): string
    {
        $controller = Controller::curr();
        $activeTagFilter = $controller->getRequest()->getVar('tag');

        if ($activeTagFilter) {
            $tag = Tag::get()->filter('Slug', $activeTagFilter)->first();
        }

        return $tag->Title ?? $this->getField('Title');
    }

    public function getControllerName(): string
    {
        return ArticlesPageController::class;
    }


    protected function onAfterWrite()
    {
        $articlePages = ArticlePage::get()->filter('ParentID', $this->ID);

        $articlePages->each(function (ArticlePage $articlePage) {
            $pinned = $articlePage->Pinned;
            $highlighted = $articlePage->Highlighted;

            if (!$highlighted && in_array($articlePage->ID, $this->HighlightedArticles()->column('ID'))) {
                $articlePage->Highlighted = true;
            }

            if ($highlighted && !in_array($articlePage->ID, $this->HighlightedArticles()->column('ID'))) {
                $articlePage->Highlighted = false;
            }

            if (!$pinned && in_array($articlePage->ID, $this->PinnedArticles()->column('ID'))) {
                $articlePage->Pinned = true;
            }

            if ($pinned && !in_array($articlePage->ID, $this->PinnedArticles()->column('ID'))) {
                $articlePage->Pinned = false;
            }

            try {
                $isModifiedOnDraft = $articlePage->isModifiedOnDraft();
                $articlePage->write();

                if (!$isModifiedOnDraft) {
                    $articlePage->publishRecursive();
                }
            } catch (\Exception $exception) {
                Injector::inst()->get('LoggingService')->exception($exception);
            }
        });

        parent::onAfterWrite();
    }
}
