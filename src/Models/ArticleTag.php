<?php

namespace Webmen\Articles\Models;

use SilverStripe\CMS\Controllers\CMSPageEditController;
use SilverStripe\ORM\DataObject;
use Webmen\Articles\Pages\ArticlePage;
use Webmen\Articles\Traits\ArticleRelationObjectTrait;

class ArticleTag extends DataObject
{
    use ArticleRelationObjectTrait;

    /***
     * @var string
     */
    private static $table_name = 'Webmen_ArticleTag';

    /***
     * @var string
     */
    private static $singular_name = 'Article tag';

    /***
     * @var string
     */
    private static $plural_name = 'Article tags';

    /***
     * @var string
     */
    private static $icon_class = 'font-icon-rocket';

    /**
     * @var array
     */
    private static $summary_fields = [
        'Title' => 'Tag name',
    ];

    /**
     * @var array
     */
    private static $many_many = [
        'ArticlePages' => ArticlePage::class,
    ];

    /***
     * This sets the ArticlesPageID in case the ArticleTag is created within
     * an article {@see ArticlePage}, in stead of via the article overview page {@see ArticlesPage}
     */
    protected function onBeforeWrite()
    {
        $currentPageID = CMSPageEditController::curr()->currentPageID();
        $currentPage = \Page::get_by_id(ArticlePage::class, $currentPageID);

        if ($currentPage) {
            $this->ArticlesPageID = $currentPage->ParentID;
        }

        parent::onBeforeWrite();
    }
}
