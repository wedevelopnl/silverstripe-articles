<?php

namespace TheWebmen\Articles\Pages;

use SilverStripe\Control\RSS\RSSFeed;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\ManyManyList;
use SilverStripe\ORM\PaginatedList;
use SilverStripe\ORM\SS_List;

class ArticlesPageController extends \PageController
{

    /**
     * @var array
     */
    private static $allowed_actions = [
        'rss'
    ];

    /**
     * Init
     */
    public function init()
    {
        parent::init();
        RSSFeed::linkToFeed($this->Link('rss'), _t(self::class . '.RSS_TITLE', '10 Most Recently Updated Articles'));
    }

    /**
     * @return \SilverStripe\ORM\FieldType\DBHTMLText
     */
    public function rss()
    {
        $this->extend('beforeRss');

        $rss = new RSSFeed(
            $this->LatestArticles(),
            $this->Link(),
            _t(self::class . '.RSS_TITLE', '10 Most Recently Updated Articles'),
            _t(self::class . '.RSS_DESCRIPTION', 'Shows a list of the 10 most recently updated articles.'),
            'Title',
            'Content',
            'AuthorName'
        );
        return $rss->outputToBrowser();
    }

    /**
     * @return static
     */
    public function LatestArticles()
    {
        return ArticlePage::get()->filter(array(
            'ParentID' => $this->ID
        ))->sort('LastEdited', 'DESC')->limit(10);
    }

    /**
     * @return SS_List
     */
    public function Articles()
    {
        $list = ArticlePage::get()->filter('ParentID', $this->ID);
        if ($this->hasMethod('updateArticles')) {
            $list = $this->updateArticles($list);
        }
        return $list;
    }

    /**
     * @return PaginatedList
     */
    public function PaginatedArticles()
    {
        $list = $this->Articles();
        $pagination = PaginatedList::create($list, $this->getRequest());
        $pagination->setPageLength($this->PageLength);
        if ($this->hasMethod('updatePaginatedArticles')) {
            $pagination = $this->updatePaginatedArticles($pagination);
        }
        return $pagination;
    }

    /**
     * @return SS_List
     */
    public function Categories()
    {
        $list = CategoryPage::get()->filter('ParentID', $this->ID);
        return $list;
    }
}
