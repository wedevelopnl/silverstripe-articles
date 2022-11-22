<?php

namespace WeDevelop\Articles\Models;

use SilverStripe\CMS\Controllers\CMSPageEditController;
use SilverStripe\Dev\TaskRunner;
use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataObject;
use SilverStripe\View\Parsers\URLSegmentFilter;
use WeDevelop\Articles\Pages\ArticlePage;
use WeDevelop\Articles\Pages\ArticlesPage;

class Tag extends DataObject
{
    /**
     * @var string
     */
    private static $table_name = 'WeDevelop_ArticleTag';

    /**
     * @var string
     */
    private static $singular_name = 'Article tag';

    /**
     * @var string
     */
    private static $plural_name = 'Article tags';

    /**
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

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName('ArticlesPageID');

        $fields->renameField('Title', _t(__CLASS__ . '.NAME', 'Name'));

        $this->extend('onAfterUpdateCMSFields', $fields);

        return $fields;
    }

    /**
     * This sets the ArticlesPageID in case the Tag is created within
     * an article {@see ArticlePage}, instead of via the article overview page {@see ArticlesPage}
     */
    protected function onBeforeWrite(): void
    {
        $currentController = CMSPageEditController::curr();
        if (!($currentController instanceof TaskRunner)) {
            $currentPageID = $currentController->currentPageID();
            $currentPage = \Page::get_by_id(ArticlePage::class, $currentPageID);

            if ($currentPage) {
                $this->ArticlesPageID = $currentPage->ParentID;
            }
        }

        $this->Slug = URLSegmentFilter::create()->filter($this->Title);

        parent::onBeforeWrite();
    }
}
