<?php

namespace Webmen\Articles\Models;

use SilverStripe\ORM\DataObject;
use Webmen\Articles\Pages\ArticlePage;
use Webmen\Articles\Traits\ArticleRelationObjectTrait;

class ArticleType extends DataObject
{
    use ArticleRelationObjectTrait;

    /***
     * @var string
     */
    private static $table_name = 'Webmen_ArticleType';

    /***
     * @var string
     */
    private static $singular_name = 'Article type';

    /***
     * @var string
     */
    private static $plural_name = 'Article types';

    /***
     * @var string
     */
    private static $icon_class = 'font-icon-tag';

    /**
     * @var array
     */
    private static $summary_fields = [
        'Title' => 'Type name',
    ];

    /**
     * @var array
     */
    private static $has_many = [
        'ArticlePages' => ArticlePage::class,
    ];
}
