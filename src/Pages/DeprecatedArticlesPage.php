<?php

namespace WeDevelop\Articles\Pages;

use SilverStripe\ORM\HasManyList;
use SilverStripe\ORM\ManyManyList;
use WeDevelop\Articles\Models\Author;
use WeDevelop\Articles\Models\DeprecatedAuthor;
use WeDevelop\Articles\Models\DeprecatedTag;

/**
 * Class ArticlesPage
 * @package WeDevelop\Articles\Pages
 *
 * @property int $PageLength
 * @method ArticlePage|HasManyList Articles()
 * @method Author Authors()
 * @method ArticlePage|ManyManyList HighlightedArticles()
 * @method ArticlePage|ManyManyList PinnedArticles()
 */
class DeprecatedArticlesPage extends \Page
{
    /**
     * @var string
     */
    private static $table_name = 'TheWebmen_ArticlesPage';

    /**
     * @var array
     */
    private static $allowed_children = [
        '*' . ArticlePage::class,
        '*' . ArticleThemePage::class,
        '*' . ArticleTypePage::class,
    ];

    /**
     * @var string
     */
    private static $default_child = DeprecatedArticlePage::class;

    /**
     * @var array
     */
    private static $db = [
        'PageLength' => 'Int',
    ];

    /**
     * @var array
     */
    private static $defaults = [
        'PageLength' => 10,
    ];

    /**
     * @var array
     */
    private static $has_many = [
        'Tags' => DeprecatedTag::class,
        'Authors' => DeprecatedAuthor::class,
    ];

    /**
     * @var array
     */
    private static $many_many = [
        'HighlightedArticles' => DeprecatedArticlePage::class,
        'PinnedArticles' => DeprecatedArticlePage::class,
    ];

    /**
     * @var array
     */
    private static $many_many_extraFields = [
        'HighlightedArticles' => [
            'HighlightedSort' => 'Int',
        ],
        'PinnedArticles' => [
            'PinnedSort' => 'Int',
        ],
    ];
}
