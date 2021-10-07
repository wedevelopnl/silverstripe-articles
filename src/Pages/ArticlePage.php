<?php

namespace TheWebmen\Articles\Pages;

use App\Pages\ArticleTagPage;
use App\Pages\ArticleThemePage;
use App\Pages\ArticleTypePage;
use SilverStripe\Assets\Image;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Control\Controller;
use SilverStripe\Core\Config\Config;
use SilverStripe\Forms\CompositeField;
use SilverStripe\Forms\DatetimeField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldGroup;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\ListboxField;
use SilverStripe\Forms\MultiSelectField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\ToggleCompositeField;
use SilverStripe\Forms\TreeDropdownField;
use SilverStripe\TagField\TagField;
use Symbiote\MultiValueField\Fields\MultiValueDropdownField;
use Symbiote\MultiValueField\Fields\MultiValueListField;
use TheWebmen\Articles\Models\Tag;
use TheWebmen\Articles\Models\Type;

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
        'AuthorName' => 'Varchar(255)',
        'Subtitle' => 'Varchar(255)',
        'PublicationDate' => 'Datetime',
        'UpdatedDate' => 'Datetime',
        'ReadingTime' => 'Int(3)',
        'TeaserText' => 'HTMLText',
    ];

    /***
     * @var array
     */
    private static $has_one = [
        'Thumbnail' => Image::class,
        'Type' => Type::class,
    ];

    /***
     * @var array
     */
    private static $belongs_many_many = [
        'Tags' => Tag::class,
    ];

    /***
     * @var string
     */
    private static $default_sort = 'PublicationDate DESC';

    /***
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName('MenuTitle');

        $title = $fields->dataFieldByName('Title')->setTitle('Article title');

        $fields->insertAfter(
            'URLSegment',
            TextField::create('Subtitle', 'Article subtitle')
        );

        $fields->insertAfter(
            'Subtitle',
            TextField::create('AuthorName', 'Author name')
        );

        $fields->replaceField('Content', HTMLEditorField::create('Content'));

        $fields->insertAfter(
            'AuthorName',
            FieldGroup::create(
                [
                    TextField::create('ReadingTime', 'Reading time (in min.)'),
                    DatetimeField::create('PublicationDate', 'Publication date'),
                    DatetimeField::create('UpdatedDate', 'Updated date'),
                ]
            )
                ->setName('ArticleMetadata')
                ->setTitle('Article metadata')
        );

        $fields->insertAfter(
            'ArticleMetadata',
            TagField::create(
                'Tags',
                'Tags',
                Tag::get()->filter(
                    [
                        'ArticlesPageID' => $this->ParentID
                    ]
                ),
                $this->Tags()
            )
        );

        $fields->insertAfter(
            'Tags',
            DropdownField::create(
                'TypeID',
                'Type',
                Type::get()->filter(
                    [
                        'ArticlesPageID' => $this->ParentID
                    ]
                )
            )
        );

        $fields->insertAfter(
            'ArticleMetadata',
            HTMLEditorField::create('TeaserText', 'Teaser text')
                ->setRows(5)
        );

        return $fields;
    }

    /***
     * @return string
     */
    public function getControllerName()
    {
        return ArticlePageController::class;
    }
}
