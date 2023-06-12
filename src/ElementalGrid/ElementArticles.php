<?php

namespace WeDevelop\Articles\ElementalGrid;

use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\NumericField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\TreeDropdownField;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\ManyManyList;
use SilverStripe\TagField\TagField;
use UncleCheese\DisplayLogic\Forms\Wrapper;
use WeDevelop\Articles\Models\Author;
use WeDevelop\Articles\Pages\ArticlePage;
use WeDevelop\Articles\Pages\ArticlesPage;
use WeDevelop\Articles\Pages\ArticleThemePage;
use WeDevelop\Articles\Pages\ArticleTypePage;
use WeDevelop\Articles\Services\ArticleFilterService;

/**
 * Class ElementArticles
 * @package WeDevelop\Articles\ElementalGrid
 *
 * @method ArticlesPage ArticlesPage()
 * @method ArticleTypePage|ManyManyList Types()
 * @method ArticleThemePage|ManyManyList Themes()
 * @method Author|ManyManyList Authors()
 */
class ElementArticles extends BaseElement
{
    /**
     * @var string
     */
    private static $table_name = 'Element_Articles';

    /**
     * @var string
     */
    private static $singular_name = 'Articles list';

    /**
     * @var string
     */
    private static $plural_name = 'Articles lists';

    /**
     * @var string
     */
    private static $description = 'Show articles in a grid element';

    /**
     * @var string
     */
    private static $icon = 'font-icon-menu-campaigns';

    /**
     * @var array
     */
    private static $has_one = [
        'ArticlesPage' => ArticlesPage::class,
    ];

    /**
     * @var array
     */
    private static $belongs_many_many = [
        'Themes' => ArticleThemePage::class,
        'Types' => ArticleTypePage::class,
        'Authors' => Author::class,
    ];

    /**
     * @var array
     */
    private static $db = [
        'ShowMoreArticlesButton' => 'Boolean',
        'MaxAmount' => 'Int(3)',
        'ShowMoreArticlesButtonText' => 'Varchar(255)',
    ];

    /**
     * @var array
     */
    private static $defaults = [
        'MaxAmount' => 10,
    ];

    public function getCMSFields(): FieldList
    {
        $fields = parent::getCMSFields();

        $fields->addFieldsToTab('Root.Main', [
            TreeDropdownField::create('ArticlesPageID', _t(__CLASS__ . '.ARTICLESPAGE', 'Articles overview page'), SiteTree::class),
        ]);

        $fields->removeByName(
            [
                'Themes',
                'Types',
                'Authors',
                'ShowMoreArticlesButton',
                'MaxAmount',
                'ShowMoreArticlesButtonText',
            ]
        );

        if ($this->exists() && $this->ArticlesPageID !== 0 && $this->ArticlesPage()->exists()) {
            $fields->addFieldsToTab(
                'Root.Main',
                [
                    TagField::create(
                        'Themes',
                        sprintf(
                            '%s (%s)',
                            _t('WeDevelop\Articles\Pages\ArticleThemePage.PLURALNAME', 'Themes'),
                            strtolower(_t(__CLASS__ . '.OPTIONAL', 'Optional'))
                        ),
                        ArticleThemePage::get()->filter('ParentID', $this->ArticlesPage()->ID),
                        $this->Themes()
                    )->setCanCreate(false),
                    TagField::create(
                        'Types',
                        sprintf(
                            '%s (%s)',
                            _t('WeDevelop\Articles\Pages\ArticleTypePage.PLURALNAME', 'Types'),
                            strtolower(_t(__CLASS__ . '.OPTIONAL', 'Optional'))
                        ),
                        ArticleTypePage::get()->filter('ParentID', $this->ArticlesPage()->ID),
                        $this->Types()
                    )->setCanCreate(false),
                    TagField::create(
                        'Authors',
                        sprintf(
                            '%s (%s)',
                            _t('WeDevelop\Articles\Models\Author.PLURALNAME', 'Authors'),
                            strtolower(_t(__CLASS__ . '.OPTIONAL', 'Optional'))
                        ),
                        $this->ArticlesPage()->Authors(),
                        $this->Authors()
                    )->setCanCreate(false),
                    NumericField::create(
                        'MaxAmount',
                        _t(__CLASS__ . '.MAXAMOUNT', 'Max. amount of articles shown')
                    ),
                    CheckboxField::create(
                        'ShowMoreArticlesButton',
                        _t(__CLASS__ . '.SHOWMOREBUTTON', "Show 'more articles' button")
                    ),
                    Wrapper::create([
                        TextField::create(
                            'ShowMoreArticlesButtonText',
                            _t(__CLASS__ . '.SHOWMOREBUTTONTEXT', "Show 'more articles' button text")
                        ),
                    ])
                        ->displayIf('ShowMoreArticlesButton')
                        ->isChecked()
                        ->end(),
                ]
            );
        }

        $this->extend('onAfterUpdateCMSFields', $fields);

        return $fields;
    }

    public function getType(): string
    {
        return 'Articles overview';
    }

    public function getArticles(): ?DataList
    {
        $articles = ArticlePage::get()->filter('ParentID', $this->ArticlesPage()->ID);

        if ($articles) {
            $articles = $this->applyFilters($articles);

            $this->extend('updateArticles', $articles);

            return $articles->limit($this->MaxAmount);
        }

        return null;
    }

    private function applyFilters(DataList $articles): DataList
    {
        $filterService = new ArticleFilterService($articles);

        if ($this->Themes()->count()) {
            $filterService->applyThemesFilter($this->Themes()->column('URLSegment'));
        }

        if ($this->Types()->count()) {
            $filterService->applyTypeFilter($this->Types()->column('URLSegment'));
        }

        $articles = $filterService->getArticles();

        if ($this->Authors()->count()) {
            $articles = $articles->filter('Author.Slug', $this->Authors()->column('Slug'));
        }

        return $articles;
    }
}
