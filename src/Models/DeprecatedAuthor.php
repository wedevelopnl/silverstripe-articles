<?php

namespace WeDevelop\Articles\Models;

use SilverStripe\ORM\DataObject;
use SilverStripe\Assets\Image;
use WeDevelop\Articles\Pages\ArticlesPage;

class DeprecatedAuthor extends DataObject
{
    /**
     * @var string
     */
    private static $table_name = 'Webmen_Author';

    /**
     * @var array
     */
    private static $db = [
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
     * @var array
     */
    private static $has_one = [
        'ArticlesPage' => ArticlesPage::class,
        'Image' => Image::class,
    ];
}
