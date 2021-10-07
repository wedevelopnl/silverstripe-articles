<?php

namespace Webmen\Articles\Traits;

use SilverStripe\CMS\Controllers\CMSPageEditController;
use SilverStripe\Forms\FieldList;
use Webmen\Articles\Pages\ArticlePage;
use Webmen\Articles\Pages\ArticlesPage;

trait ArticleRelationObjectTrait
{
    /**
     * @var array
     */
    private static $db = [
        'Title' => 'Varchar(255)',
    ];

    /**
     * @var array
     */
    private static $has_one = [
        'ArticlesPage' => ArticlesPage::class,
    ];

    /***
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName('ArticlesPageID');

        $fields->renameField('Title', 'Name');

        return $fields;
    }
}
