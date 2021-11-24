<?php

namespace TheWebmen\Articles\Pages;

use SilverStripe\Assets\Image;
use SilverStripe\Forms\DatetimeField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldGroup;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\TextField;
use SilverStripe\TagField\TagField;
use TheWebmen\Articles\Models\Author;
use TheWebmen\Articles\Models\Tag;

class ArticlePage extends \Page
{
    /**
     * @var string
     */
    private static $table_name = 'TheWebmen_ArticlePage';

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
    private static $belongs_many_many = [
        'Tags' => Tag::class,
        'Themes' => ArticleThemePage::class,
        'HighlightedArticles' => ArticlesPage::class . '.HighlightedArticles',
        'PinnedArticles' => ArticlesPage::class . '.PinnedArticles',
    ];

    /**
     * @var array
     */
    private static $default_sort = [
        'PublicationDate' => 'DESC',
    ];

    public function getCMSFields(): FieldList
    {
        $fields = parent::getCMSFields();

        $fields->removeByName('MenuTitle');

        $title = $fields->dataFieldByName('Title')->setTitle(_t('Article.Title', 'Article Title'));

        $fields->insertAfter(
            'URLSegment',
            TextField::create('Subtitle', _t('Article.Subtitle', 'Article subtitle'))
        );

        $fields->replaceField('Content', HTMLEditorField::create('Content'));

        $fields->insertAfter(
            'Subtitle',
            FieldGroup::create(
                [
                    TextField::create('ReadingTime', _t('Article.ReadingTime', 'Reading time (in min.)')),
                    DatetimeField::create('PublicationDate', _t('Article.Date.Publication', 'Publication date')),
                    DatetimeField::create('UpdatedDate', _t('Article.Date.Updated', 'Updated date')),
                ]
            )
                ->setName('ArticleMetadata')
                ->setTitle(_t('Article.Metadata', 'Article metadata'))
        );

        $fields->insertAfter(
            'ArticleMetadata',
            TagField::create(
                'Themes',
                _t('Theme.Plural', 'Themes'),
                ArticleThemePage::get()->filter('ParentID', $this->ParentID),
                $this->Themes()
            )->setCanCreate(false)
        );

        $fields->insertAfter(
            'Themes',
            TagField::create(
                'Tags',
                _t('Tag.Plural', 'Tags'),
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
                _t('Author.Singular', 'Author'),
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
                _t('Type.Singular', 'Type'),
                ArticleTypePage::get()->filter(
                    [
                        'ParentID' => $this->ParentID
                    ]
                )
            )
                ->setHasEmptyDefault(true)
        );

        $fields->insertAfter(
            'TypeID',
            HTMLEditorField::create('TeaserText', _t('Article.TeaserText', 'Teaser text'))
                ->setRows(5)
        );

        return $fields;
    }
}
