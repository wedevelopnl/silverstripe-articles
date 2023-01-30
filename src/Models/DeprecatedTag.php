<?php

namespace WeDevelop\Articles\Models;

use SilverStripe\ORM\DataObject;
use WeDevelop\Articles\Pages\ArticlePage;
use WeDevelop\Articles\Pages\ArticlesPage;

class DeprecatedTag extends DataObject
{
    /**
     * @var string
     */
    private static $table_name = 'Webmen_ArticleTag';

    /**
     * @var array
     */
    private static $db = [
        'Title' => 'Varchar(255)',
        'Slug' => 'Varchar(255)',
    ];

    /**
     * @var array
     */
    private static $has_one = [
        'ArticlesPage' => ArticlesPage::class,
    ];

    /**
     * @var array
     */
    private static $many_many = [
        'ArticlePages' => ArticlePage::class,
    ];
}
