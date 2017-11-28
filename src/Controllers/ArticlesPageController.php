<?php

namespace TheWebmen\Articles\Pages;

use SilverStripe\Control\RSS\RSSFeed;
use SilverStripe\ORM\PaginatedList;

class ArticlesPageController extends \PageController {

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
        RSSFeed::linkToFeed($this->Link() . 'rss', _t(self::class . '.RSS_TITLE', '10 Most Recently Updated Articles'));
    }

    /**
     * @return \SilverStripe\ORM\FieldType\DBHTMLText
     */
    public function rss()
    {
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
     * @return PaginatedList
     */
    public function PaginatedArticles()
    {
        $list = ArticlePage::get()->filter('ParentID', $this->ID);
        $pagination = PaginatedList::create($list, $this->getRequest());
        $pagination->setPageLength($this->PageLength);
        return $pagination;
    }

}
