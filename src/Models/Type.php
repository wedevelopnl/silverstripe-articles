<?php

namespace TheWebmen\Articles\Models;

use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataObject;
use SilverStripe\View\Parsers\URLSegmentFilter;
use TheWebmen\Articles\ElementalGrid\ElementArticles;
use TheWebmen\Articles\Pages\ArticlePage;
use TheWebmen\Articles\Pages\ArticlesPage;

class Type extends DataObject
{
    /**
     * @var array
     */
    private static $db = [
        'Title' => 'Varchar(255)',
        'Slug' => 'Varchar(255)',
    ];

    /**
     * @var string
     */
    private static $table_name = 'Webmen_ArticleType';

    /**
     * @var string
     */
    private static $singular_name = 'Type';

    /**
     * @var string
     */
    private static $plural_name = 'Types';

    /**
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
    private static $has_one = [
        'ArticlesPage' => ArticlesPage::class,
    ];

    /**
     * @var array
     */
    private static $has_many = [
        'ArticlePages' => ArticlePage::class,
    ];

    /**
     * @var array
     */
    private static $many_many = [
        'ElementArticles' => ElementArticles::class,
    ];

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName('ArticlesPageID');

        $fields->renameField('Title', 'Name');

        return $fields;
    }

    protected function onBeforeWrite(): void
    {
        $this->Slug = URLSegmentFilter::create()->filter($this->Title);

        parent::onBeforeWrite();
    }
}
