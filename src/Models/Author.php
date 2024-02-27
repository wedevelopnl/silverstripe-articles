<?php

namespace WeDevelop\Articles\Models;

use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataObject;
use WeDevelop\Articles\ElementalGrid\ElementArticles;
use WeDevelop\Articles\Pages\ArticlesPage;

class Author extends DataObject
{
    /** @config */
    private static string $table_name = 'WeDevelop_Author';

    /** @config */
    private static string $singular_name = 'Author';

    /** @config */
    private static string $plural_name = 'Authors';

    /** @config */
    private static string $icon_class = 'font-icon-block-user';

    /**
     * @config
     * @var array<string, string>
     */
    private static array $summary_fields = [
        'Title' => 'Name',
    ];

    /**
     * @config
     * @var array<string, string>
     */
    private static array $db = [
        'Title' => 'Varchar(255)',
        'Slug' => 'Varchar(255)',
        'Function' => 'Varchar',
        'Bio' => 'HTMLText',
        'Phone' => 'Varchar',
        'Email' => 'Varchar',
        'FacebookURL' => 'Varchar',
        'TwitterURL' => 'Varchar',
        'LinkedInURL' => 'Varchar',
    ];

    /**
     * @config
     * @var array<string, class-string>
     */
    private static array $has_one = [
        'ArticlesPage' => ArticlesPage::class,
        'Image' => Image::class,
    ];

    /**
     * @config
     * @var array<string, class-string>
     */
    private static array $many_many = [
        'ElementArticles' => ElementArticles::class,
    ];

    /**
     * @config
     * @var array<string>
     */
    private static array $owns = [
        'Image',
    ];

    public function getCMSFields(): FieldList
    {
        $this->beforeUpdateCMSFields(function (FieldList $fields) {
            $fields->removeByName(
                [
                    'ArticlesPageID',
                    'Slug',
                    'Function',
                    'Bio',
                    'Phone',
                    'Email',
                    'FacebookURL',
                    'TwitterURL',
                    'LinkedInURL',
                ]
            );

            $fields->renameField('Title', 'Name');

            $fields->addFieldsToTab(
                'Root.Main',
                [
                    TextField::create('Function', 'Function'),
                    UploadField::create('Image', 'Image')->setFolderName('Authors'),
                    HTMLEditorField::create('Bio', 'Bio')->setRows(5),
                    HeaderField::create('ContactDetails', 'Contact details'),
                    TextField::create('Phone', 'Phone'),
                    TextField::create('Email', 'E-mailaddress'),
                    HeaderField::create('SocialMedia', 'Social media'),
                    TextField::create('FacebookURL', 'Facebook URL'),
                    TextField::create('TwitterURL', 'Twitter URL'),
                    TextField::create('LinkedInURL', 'LinkedIn URL'),
                ]
            );
        });

        $fields = parent::getCMSFields();
        $this->extend('onAfterUpdateCMSFields', $fields);
        return  $fields;
    }
}
