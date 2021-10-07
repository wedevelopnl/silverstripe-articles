<?php

namespace TheWebmen\Articles\Models;

use SilverStripe\ORM\DataObject;
use TheWebmen\Articles\Pages\ArticlePage;
use TheWebmen\Articles\Traits\ArticleRelationObjectTrait;

class Type extends DataObject
{
    use ArticleRelationObjectTrait;

    /***
     * @var string
     */
    private static $table_name = 'Webmen_ArticleType';

    /***
     * @var string
     */
    private static $singular_name = 'Type';

    /***
     * @var string
     */
    private static $plural_name = 'Types';

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
