<?php

namespace Webmen\Articles\Pages;

use SilverStripe\Assets\Image;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Core\Config\Config;
use SilverStripe\Forms\DatetimeField;
use SilverStripe\Forms\FieldGroup;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\TextField;

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
        'Thumbnail' => Image::class
    ];

    /***
     * @var string
     */
    private static $default_sort = 'PublicationDate DESC';

    private function getInsertBeforeFieldname()
    {
        $gridInstalled = Config::inst()->exists('DNADesign\\Elemental\\Models\\ElementalArea');

        if ($gridInstalled && $this->owner->UseElementalGrid) {
            return 'ElementalArea';
        }

        return 'Content';
    }

    /***
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName('MenuTitle');
        $fields->renameField('Title', 'Title');

        $fields->insertAfter(
            'Title',
            TextField::create('Subtitle', 'Subtitle')
        );

        if ($fields->dataFieldByName('ElementalArea')) {
            $insertBefore = 'ElementalArea';
        } else {
            $insertBefore = 'Content';
        }

        $fields->addFieldsToTab(
            'Root.Main',
            [
                FieldGroup::create(
                    [
                        TextField::create('ReadingTime', 'Reading time (min.)'),
                        DatetimeField::create('PublicationDate', 'Publication date'),
                        DatetimeField::create('UpdatedDate', 'Updated date'),
                    ]
                )->setName('Configuration'),
                TextField::create('AuthorName', 'Author name'),
                HTMLEditorField::create('TeaserText', 'Teaser text')
                    ->setRows(5)
            ]
        , $insertBefore);

        return $fields;
    }
}
