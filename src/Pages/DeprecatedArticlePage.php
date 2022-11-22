<?php

namespace WeDevelop\Articles\Pages;

use SilverStripe\Assets\Image;
use WeDevelop\Articles\Models\DeprecatedAuthor;

class DeprecatedArticlePage extends \Page
{
    /**
     * @var string
     */
    private static $table_name = 'TheWebmen_ArticlePage';

    /**
     * @var array
     */
    private static $db = [
        'Subtitle' => 'Varchar(255)',
        'PublicationDate' => 'Datetime',
        'UpdatedDate' => 'Datetime',
        'ReadingTime' => 'Int(3)',
        'TeaserText' => 'HTMLText',
        'Pinned' => 'Boolean',
        'Highlighted' => 'Boolean',
    ];

    /**
     * @var array
     */
    private static $has_one = [
        'Thumbnail' => Image::class,
        'Type' => DeprecatedArticleTypePage::class,
        'Author' => DeprecatedAuthor::class,
    ];

    /**
     * @var array
     */
    private static $owns = [
        'Thumbnail',
    ];

    /**
     * @var array
     */
    private static $belongs_many_many = [
        'Tags' => DeprecatedTag::class,
        'Themes' => DeprecatedArticleThemePage::class,
        'HighlightedArticles' => DeprecatedArticlesPage::class . '.HighlightedArticles',
        'PinnedArticles' => DeprecatedArticlesPage::class . '.PinnedArticles',
    ];
}
