<?php

namespace WeDevelop\Articles\Pages;

use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\DatetimeField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldGroup;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\Forms\GridField\GridFieldEditButton;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\FieldType\DBDatetime;
use SilverStripe\TagField\TagField;
use SilverStripe\Versioned\GridFieldArchiveAction;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use WeDevelop\Articles\Controllers\ArticlePageController;
use WeDevelop\Articles\Controllers\ArticlesPageController;
use WeDevelop\Articles\Models\Author;
use WeDevelop\Articles\Models\Tag;

class ArticlePage extends \Page
{
    private static string $table_name = 'WeDevelop_ArticlePage';

    private static string $singular_name = 'Article page';

    private static string $description = 'A page that represents an article';

    private static string $plural_name = 'Articles page';

    private static string $icon_class = 'font-icon-p-article';

    private static bool $show_in_sitetree = false;

    private static bool $can_be_root = false;

    private static array $allowed_children = [];

    private static array $db = [
        'Subtitle' => 'Varchar(255)',
        'PublicationDate' => 'Datetime',
        'UpdatedDate' => 'Datetime',
        'ReadingTime' => 'Int(3)',
        'TeaserText' => 'HTMLText',
        'Pinned' => 'Boolean',
        'Highlighted' => 'Boolean',
    ];

    private static array $has_one = [
        'Thumbnail' => Image::class,
        'Type' => ArticleTypePage::class,
        'Author' => Author::class,
    ];

    private static array $many_many = [
        'RelatedArticles' => ArticlePage::class,
    ];

    private static array $owns = [
        'Thumbnail',
    ];

    private static array $belongs_many_many = [
        'Tags' => Tag::class,
        'Themes' => ArticleThemePage::class,
        'HighlightedArticles' => ArticlesPage::class . '.HighlightedArticles',
        'PinnedArticles' => ArticlesPage::class . '.PinnedArticles',
    ];

//    private static string $default_sort = 'PublicationDate DESC';

    public function getCMSFields(): FieldList
    {
        $this->beforeUpdateCMSFields(function (FieldList $fields) {
            $fields->removeByName('MenuTitle');

            $fields->addFieldsToTab('Root.RelatedArticles', [
                GridField::create(
                    'RelatedArticles',
                    _t(__CLASS__ . '.RELATED_ARTICLES', 'Related articles'),
                    $this->owner->RelatedArticles(),
                    GridFieldConfig_RelationEditor::create()
                        ->addComponent(new GridFieldOrderableRows())
                        ->removeComponentsByType(GridFieldArchiveAction::class)
                        ->removeComponentsByType(GridFieldEditButton::class)
                ),
            ]);

            $fields->insertAfter(
                'Title',
                TextField::create('Subtitle', _t(__CLASS__ . '.SUBTITLE', 'Subtitle'))
            );

            $fields->replaceField('Content', HTMLEditorField::create('Content'));

            $fields->addFieldsToTab('Root.Metadata', [
                FieldGroup::create(
                    [
                        TextField::create('ReadingTime', _t(__CLASS__ . '.READINGTIME', 'Reading time (in min.)')),
                        DatetimeField::create('PublicationDate', _t(__CLASS__ . '.PUBLICATIONDATE', 'Publication date')),
                        DatetimeField::create('UpdatedDate', _t(__CLASS__ . '.UPDATEDATE', 'Update date')),
                    ]
                )
                    ->setName('ArticleMetadata')
                    ->setTitle(_t(__CLASS__ . '.METADATA', 'Metadata')),
                TagField::create(
                    'Themes',
                    _t('WeDevelop\Articles\Pages\ArticleThemePage.PLURALNAME', 'Themes'),
                    ArticleThemePage::get()->filter('ParentID', $this->ParentID),
                    $this->Themes()
                )->setCanCreate(false),

                TagField::create(
                    'Tags',
                    _t('WeDevelop\Articles\Models\Tag.PLURALNAME', 'Tags'),
                    Tag::get()->filter(
                        [
                            'ArticlesPageID' => $this->ParentID,
                        ]
                    ),
                    $this->Tags()
                ),

                DropdownField::create(
                    'AuthorID',
                    _t('WeDevelop\Articles\Models\Author.SINGULARNAME', 'Author'),
                    Author::get()->filter(
                        [
                            'ArticlesPageID' => $this->ParentID,
                        ]
                    )
                )
                    ->setHasEmptyDefault(true),
                DropdownField::create(
                    'TypeID',
                    _t('WeDevelop\Articles\Pages\ArticleTypePage.SINGULARNAME', 'Type'),
                    ArticleTypePage::get()->filter(
                        [
                            'ParentID' => $this->ParentID,
                        ]
                    )
                )
                    ->setHasEmptyDefault(true),
                HTMLEditorField::create(
                    'TeaserText',
                    _t(__CLASS__ . '.TEASERTEXT', 'Teaser text')
                )
                    ->setRows(5),
            ]);

            $fields->insertAfter(
                'TeaserText',
                UploadField::create(
                    'Thumbnail',
                    _t(__CLASS__ . '.THUMBNAIL', 'Thumbnail')
                )
                ->setFolderName('Thumbnails')
            );
        });

        $fields = parent::getCMSFields();
        $this->extend('onAfterUpdateCMSFields', $fields);
        return  $fields;
    }

    public function getControllerName(): string
    {
        return ArticlePageController::class;
    }

    protected function onBeforeWrite(): void
    {
        if (is_null($this->PublicationDate)) {
            $this->PublicationDate = DBDatetime::now()->getValue();
        }

        parent::onBeforeWrite();
    }
}
