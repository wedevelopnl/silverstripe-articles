<?php

namespace WeDevelop\Articles\Pages;

use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\DatetimeField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldGroup;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\FieldType\DBDatetime;
use SilverStripe\TagField\TagField;
use WeDevelop\Articles\Controllers\ArticlesPageController;
use WeDevelop\Articles\Models\Author;
use WeDevelop\Articles\Models\Tag;

class ArticlePage extends \Page
{
    /**
     * @var string
     */
    private static $table_name = 'WeDevelop_ArticlePage';

    /**
     * @var string
     */
    private static $singular_name = 'Article page';

    /**
     * @var string
     */
    private static $description = 'A page that represents an article';

    /**
     * @var string
     */
    private static $plural_name = 'Articles page';

    /**
     * @var string
     */
    private static $icon_class = 'font-icon-p-article';

    /**
     * @var bool
     */
    private static $show_in_sitetree = false;

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
    private static $db = [
        'Subtitle' => 'Varchar(255)',
        'PublicationDate' => 'Datetime',
        'UpdatedDate' => 'Datetime',
        'ReadingTime' => 'Int(3)',
        'TeaserText' => 'HTMLText',
        'Pinned' => 'Boolean',
        'Highlighted' => 'Boolean',
    ];

    /**
     * @var array
     */
    private static $has_one = [
        'Thumbnail' => Image::class,
        'Type' => ArticleTypePage::class,
        'Author' => Author::class,
    ];

    /**
     * @var array
     */
    private static $owns = [
        'Thumbnail',
    ];

    /**
     * @var array
     */
    private static $belongs_many_many = [
        'Tags' => Tag::class,
        'Themes' => ArticleThemePage::class,
        'HighlightedArticles' => ArticlesPage::class . '.HighlightedArticles',
        'PinnedArticles' => ArticlesPage::class . '.PinnedArticles',
    ];

    /**
     * @var string
     */
    private static $default_sort = 'PublicationDate DESC';


    public function getCMSFields(): FieldList
    {
        $this->beforeUpdateCMSFields(function (FieldList $fields) {
            $fields->removeByName('MenuTitle');

            $fields->insertAfter(
                'URLSegment',
                TextField::create('Subtitle', _t(__CLASS__ . '.SUBTITLE', 'Subtitle'))
            );

            $fields->replaceField('Content', HTMLEditorField::create('Content'));

            $fields->insertAfter(
                'Subtitle',
                FieldGroup::create(
                    [
                        TextField::create('ReadingTime', _t(__CLASS__ . '.READINGTIME', 'Reading time (in min.)')),
                        DatetimeField::create('PublicationDate', _t(__CLASS__ . '.PUBLICATIONDATE', 'Publication date')),
                        DatetimeField::create('UpdatedDate', _t(__CLASS__ . '.UPDATEDATE', 'Update date')),
                    ]
                )
                ->setName('ArticleMetadata')
                ->setTitle(_t(__CLASS__ . '.METADATA', 'Metadata'))
            );

            $fields->insertAfter(
                'ArticleMetadata',
                TagField::create(
                        'Themes',
                        _t('WeDevelop\Articles\Pages\ArticleThemePage.PLURALNAME', 'Themes'),
                        ArticleThemePage::get()->filter('ParentID', $this->ParentID),
                        $this->Themes()
                    )->setCanCreate(false)
            );

            $fields->insertAfter(
                'Themes',
                TagField::create(
                    'Tags',
                    _t('WeDevelop\Articles\Models\Tag.PLURALNAME', 'Tags'),
                    Tag::get()->filter(
                        [
                            'ArticlesPageID' => $this->ParentID,
                        ]
                    ),
                    $this->Tags()
                )
            );

            $fields->insertAfter(
                'Tags',
                DropdownField::create(
                    'AuthorID',
                    _t('WeDevelop\Articles\Models\Author.SINGULARNAME', 'Author'),
                    Author::get()->filter(
                        [
                            'ArticlesPageID' => $this->ParentID,
                        ]
                    )
                )
                ->setHasEmptyDefault(true)
            );

            $fields->insertAfter(
                'AuthorID',
                DropdownField::create(
                    'TypeID',
                    _t('WeDevelop\Articles\Pages\ArticleTypePage.SINGULARNAME', 'Type'),
                    ArticleTypePage::get()->filter(
                        [
                            'ParentID' => $this->ParentID,
                        ]
                    )
                )
                ->setHasEmptyDefault(true)
            );

            $fields->insertAfter(
                'TypeID',
                HTMLEditorField::create(
                    'TeaserText',
                    _t(__CLASS__ . '.TEASERTEXT', 'Teaser text')
                )
                ->setRows(5)
            );

            $fields->insertAfter(
                'TeaserText',
                UploadField::create(
                    'Thumbnail',
                    _t(__CLASS__ . '.THUMBNAIL', 'Thumbnail')
                )
                ->setFolderName('Thumbnails')
            );
        });

        $this->extend('onAfterUpdateCMSFields', $fields);

        return parent::getCMSFields();
    }

    public function getControllerName(): string
    {
        return ArticlesPageController::class;
    }

    protected function onBeforeWrite()
    {
        if (is_null($this->PublicationDate)) {
            $this->PublicationDate = DBDatetime::now()->getValue();
        }

        parent::onBeforeWrite();
    }
}
