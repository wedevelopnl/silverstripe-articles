<?php

namespace WeDevelop\Articles\Pages;

use SilverStripe\Forms\TextField;
use WeDevelop\SiteTreeButtons\GridFieldAddNewSiteTreeItemButton;
use SilverStripe\Control\Controller;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldAddExistingAutocompleter;
use SilverStripe\Forms\GridField\GridFieldAddNewButton;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\Forms\GridField\GridFieldDeleteAction;
use SilverStripe\Forms\GridField\GridFieldEditButton;
use SilverStripe\Forms\NumericField;
use SilverStripe\Lumberjack\Forms\GridFieldConfig_Lumberjack;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\DB;
use SilverStripe\ORM\HasManyList;
use SilverStripe\ORM\ManyManyList;
use SilverStripe\Versioned\GridFieldArchiveAction;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use WeDevelop\Articles\Controllers\ArticlesPageController;
use WeDevelop\Articles\GridFieldActions\ArticlesGridFieldAddExistingAutocompleter;
use WeDevelop\Articles\GridFieldActions\ArticlesGridFieldDeleteAction;
use WeDevelop\Articles\Models\Author;
use WeDevelop\Articles\Models\Tag;

/**
 * Class ArticlesPage
 * @package WeDevelop\Articles\Pages
 *
 * @property int $PageLength
 * @method ArticlePage|HasManyList Articles()
 * @method Author Authors()
 * @method ArticlePage|ManyManyList HighlightedArticles()
 * @method ArticlePage|ManyManyList PinnedArticles()
 */
class ArticlesPage extends \Page
{
    private static string $table_name = 'WeDevelop_ArticlesPage';

    private static string $singular_name = 'Articles overview page';

    private static string $plural_name = 'Articles overview pages';

    private static string $icon_class = 'font-icon-p-article';

    private static array $allowed_children = [
        ArticlePage::class,
        ArticleThemePage::class,
        ArticleTypePage::class,
    ];

    private static string $default_child = ArticlePage::class;

    private static array $db = [
        'RelatedArticlesTitle' => 'Varchar',
        'PageLength' => 'Int',
    ];

    private static array $defaults = [
        'PageLength' => 10,
    ];

    private static array $has_many = [
        'Tags' => Tag::class,
        'Authors' => Author::class,
    ];

    private static array $many_many = [
        'HighlightedArticles' => ArticlePage::class,
        'PinnedArticles' => ArticlePage::class,
    ];

    private static array $many_many_extraFields = [
        'HighlightedArticles' => [
            'HighlightedSort' => 'Int',
        ],
        'PinnedArticles' => [
            'PinnedSort' => 'Int',
        ],
    ];

    public function getCMSFields(): FieldList
    {
        $this->beforeUpdateCMSFields(function (FieldList $fields) {

            $fields->removeByName([
                'PageLength',
            ]);

            $fields->addFieldToTab(
                'Root.Themes',
                GridField::create(
                    'Themes',
                    _t('WeDevelop\Articles\Pages\ArticleThemePage.PLURALNAME', 'Themes'),
                    ArticleThemePage::get()->filter('ParentID', $this->ID),
                    GridFieldConfig_RecordEditor::create()
                        ->removeComponentsByType(GridFieldAddNewButton::class)
                        ->addComponent(new GridFieldAddNewSiteTreeItemButton())
                ),
            );

            $fields->addFieldToTab(
                'Root.Types',
                GridField::create(
                    'Types',
                    _t('WeDevelop\Articles\Pages\ArticleTypePage.PLURALNAME', 'Types'),
                    ArticleTypePage::get()->filter('ParentID', $this->ID),
                    GridFieldConfig_RecordEditor::create()
                        ->removeComponentsByType(GridFieldAddNewButton::class)
                        ->addComponent(new GridFieldAddNewSiteTreeItemButton())
                ),
            );

            $fields->addFieldsToTab(
                'Root.Authors',
                [
                    GridField::create(
                        'Authors',
                        _t('WeDevelop\Articles\Models\Author.PLURALNAME', 'Authors'),
                        $this->Authors(),
                        GridFieldConfig_RecordEditor::create()
                    ),
                ]
            );

            $fields->addFieldsToTab(
                'Root.Tags',
                [
                    GridField::create('Tags', _t('WeDevelop\Articles\Models\Tag.PLURALNAME', 'Tags'), $this->Tags(), new GridFieldConfig_RecordEditor()),
                ]
            );

            $fields->addFieldToTab(
                'Root.Highlighted',
                new GridField(
                    'HighlightedArticles',
                    _t(__CLASS__ . '.HIGHLIGHTEDARTICLES', 'Highlighted articles'),
                    $this->HighlightedArticles(),
                    $this->getGridConfig('HighlightedSort')
                )
            );

            $fields->addFieldToTab(
                'Root.Pinned',
                new GridField(
                    'PinnedArticles',
                    _t(__CLASS__ . '.PINNEDARTICLES', 'Pinned articles'),
                    $this->PinnedArticles(),
                    $this->getGridConfig('PinnedSort')
                )
            );

            $fields->addFieldsToTab(
                'Root.Settings',
                [
                    TextField::create('RelatedArticlesTitle', 'Title above related articles'),
                ]
            );
        });

        $fields = parent::getCMSFields();

        $fields->removeByName(['PageLength']);

        $fields->addFieldsToTab('Root.ChildPages', NumericField::create('PageLength'), 'ChildPages');

        return $fields;
    }

    private function getGridConfig(string $sortColumn): GridFieldConfig_RelationEditor
    {
        $gridfieldConfig = GridFieldConfig_RelationEditor::create();
        $gridfieldConfig->addComponent(new GridFieldOrderableRows($sortColumn));
        $gridfieldConfig->removeComponentsByType(GridFieldAddNewButton::class);
        $gridfieldConfig->removeComponentsByType(GridFieldArchiveAction::class);
        $gridfieldConfig->removeComponentsByType(GridFieldEditButton::class);

        // Custom delete action that properly reflects the pinned/highlighted property to the removed relation
        $gridfieldConfig->removeComponentsByType(GridFieldDeleteAction::class);
        $gridfieldConfig->addComponent(new ArticlesGridFieldDeleteAction());

        // Custom add action that properly reflects the pinned/highlighted property to the added relation
        $gridfieldConfig->removeComponentsByType(GridFieldAddExistingAutocompleter::class);
        $gridfieldConfig->addComponent(new ArticlesGridFieldAddExistingAutocompleter());

        /** @var GridFieldAddExistingAutocompleter $autocompleter */
        $autocompleter = $gridfieldConfig->getComponentByType(ArticlesGridFieldAddExistingAutocompleter::class);
        $autocompleter
            ->setSearchList(
                ArticlePage::get()->filter(
                    [
                        'ParentID' => $this->ID,
                    ]
                )
            );

        return $gridfieldConfig;
    }

    public function getLumberjackTitle(): string
    {
        return _t(__CLASS__ . '.ARTICLES', 'Articles');
    }

    private function createGridField(string $type, string $title, DataList $list): GridField
    {
        return GridField::create($type, $title, $list, GridFieldConfig_Lumberjack::create());
    }

    public function getThemes(): DataList
    {
        return ArticleThemePage::get()->filter(
            [
                'ParentID' => $this->ID,
            ]
        );
    }

    public function getTypes(): DataList
    {
        return ArticleTypePage::get()->filter(
            [
                'ParentID' => $this->ID,
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
        $parentID = $this->ID;

        DB::query("
            UPDATE WeDevelop_ArticlePage SET Highlighted = 1 WHERE ID IN (
                SELECT WeDevelop_ArticlePageID FROM WeDevelop_ArticlesPage_HighlightedArticles WHERE WeDevelop_ArticlesPageID = $parentID
            ) AND ID IN (SELECT ID FROM SiteTree WHERE ParentID = $parentID)
        ");

        DB::query(
            "
            UPDATE WeDevelop_ArticlePage SET Highlighted = 0 WHERE ID NOT IN (
                SELECT WeDevelop_ArticlePageID FROM WeDevelop_ArticlesPage_HighlightedArticles WHERE WeDevelop_ArticlesPageID = $parentID
            ) AND ID IN (SELECT ID FROM SiteTree WHERE ParentID = $parentID)"
        );

        DB::query("
            UPDATE WeDevelop_ArticlePage SET Pinned = 1 WHERE ID IN (
                SELECT WeDevelop_ArticlePageID FROM WeDevelop_ArticlesPage_PinnedArticles WHERE WeDevelop_ArticlesPageID = $parentID
            ) AND ID IN (SELECT ID FROM SiteTree WHERE ParentID = $parentID)
        ");

        DB::query(
            "
            UPDATE WeDevelop_ArticlePage SET Pinned = 0 WHERE ID NOT IN (
                SELECT WeDevelop_ArticlePageID FROM WeDevelop_ArticlesPage_PinnedArticles WHERE WeDevelop_ArticlesPageID = $parentID
            ) AND ID IN (SELECT ID FROM SiteTree WHERE ParentID = $parentID)"
        );

        parent::onAfterWrite();
    }

    public function onAfterPublish()
    {
        $parentID = $this->ID;

        DB::query("
            UPDATE WeDevelop_ArticlePage_Live SET Highlighted = 1 WHERE ID IN (
                SELECT WeDevelop_ArticlePageID FROM WeDevelop_ArticlesPage_HighlightedArticles WHERE WeDevelop_ArticlesPageID = $parentID
            ) AND ID IN (SELECT ID FROM SiteTree_Live WHERE ParentID = $parentID)
        ");

        DB::query(
            "
            UPDATE WeDevelop_ArticlePage_Live SET Highlighted = 0 WHERE ID NOT IN (
                SELECT WeDevelop_ArticlePageID FROM WeDevelop_ArticlesPage_HighlightedArticles WHERE WeDevelop_ArticlesPageID = $parentID
            ) AND ID IN (SELECT ID FROM SiteTree_Live WHERE ParentID = $parentID)"
        );

        DB::query("
            UPDATE WeDevelop_ArticlePage_Live SET Pinned = 1 WHERE ID IN (
                SELECT WeDevelop_ArticlePageID FROM WeDevelop_ArticlesPage_PinnedArticles WHERE WeDevelop_ArticlesPageID = $parentID
            ) AND ID IN (SELECT ID FROM SiteTree_Live WHERE ParentID = $parentID)
        ");

        DB::query(
            "
            UPDATE WeDevelop_ArticlePage_Live SET Pinned = 0 WHERE ID NOT IN (
                SELECT WeDevelop_ArticlePageID FROM WeDevelop_ArticlesPage_PinnedArticles WHERE WeDevelop_ArticlesPageID = $parentID
            ) AND ID IN (SELECT ID FROM SiteTree_Live WHERE ParentID = $parentID)"
        );

        parent::onAfterPublish();
    }

    /**
     * @var array<string> $excluded
     */
    public function getLumberjackPagesForGridfield(array $excluded = []): DataList
    {
        return ArticlePage::get()->filter([
            'ParentID' => $this->ID,
            'ClassName' => $excluded,
        ]);
    }
}
