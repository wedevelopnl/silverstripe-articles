<?php

namespace TheWebmen\Articles\ElementalGrid;

use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\NumericField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\TreeDropdownField;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\ManyManyList;
use SilverStripe\TagField\TagField;
use TheWebmen\Articles\Models\Author;
use TheWebmen\Articles\Models\Type;
use TheWebmen\Articles\Pages\ArticlePage;
use TheWebmen\Articles\Pages\ArticlesPage;
use TheWebmen\Articles\Pages\ArticleThemePage;
use TheWebmen\Articles\Services\ArticleFilterService;

/**
 * Class ElementArticles
 * @package TheWebmen\Articles\ElementalGrid
 *
 * @method ArticlesPage ArticlesPage()
 * @method Type|ManyManyList Types()
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
    private static $icon = 'font-icon-p-list';

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
        'Types' => Type::class,
        'Authors' => Author::class,
    ];

    /**
     * @var array
     */
    private static $db = [
        'ShowHighlightedArticlesOnly' => 'Boolean',
        'ShowPinnedArticlesAtTop' => 'Boolean',
        'ShowMoreArticlesButton' => 'Boolean',
        'MaxAmount' => 'Int(3)',
        'ShowMoreArticlesButtonText' => 'Varchar(255)',
    ];

    /**
     * @var array
     */
    private static $defaults = [
        'ShowPinnedArticlesAtTop' => true,
        'MaxAmount' => 10,
    ];

    public function getCMSFields(): FieldList
    {
        $fields = parent::getCMSFields();

        $fields->addFieldsToTab(
            'Root.Main',
            [
                TreeDropdownField::create('ArticlesPageID', 'Articles page', SiteTree::class),
            ]
        );

        $fields->removeByName(
            [
                'Themes',
                'Types',
                'Authors',
                'ShowHighlightedArticlesOnly',
                'ShowPinnedArticlesAtTop',
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
                            _t('Theme.Plural', 'Themes'),
                            strtolower(_t('ElementArticles.Optional', 'Optional'))
                        ),
                        ArticleThemePage::get()->filter('ParentID', $this->ArticlesPage()->ID),
                        $this->Themes()
                    )->setCanCreate(false),
                    TagField::create(
                        'Types',
                        sprintf(
                            '%s (%s)',
                            _t('Types.Plural', 'Types'),
                            strtolower(_t('ElementArticles.Optional', 'Optional'))
                        ),
                        $this->ArticlesPage()->Types(),
                        $this->Types()
                    )->setCanCreate(false),
                    TagField::create(
                        'Authors',
                        sprintf(
                            '%s (%s)',
                            _t('Author.Plural', 'Authors'),
                            strtolower(_t('ElementArticles.Optional', 'Optional'))
                        ),
                        $this->ArticlesPage()->Authors(),
                        $this->Authors()
                    )->setCanCreate(false),
                    NumericField::create(
                        'MaxAmount',
                        _t('ElementArticles.MaxAmount', 'Max. amount of articles shown')
                    ),
                    CheckboxField::create(
                        'ShowHighlightedArticlesOnly',
                        _t('ElementArticles.ShowHighlightedArticlesOnly', 'Show highlighted articles only')
                    ),
                    CheckboxField::create(
                        'ShowPinnedArticlesAtTop',
                        _t('ElementArticles.ShowPinnedArticlesAtTop', 'Show pinned articles at top')
                    ),
                    CheckboxField::create(
                        'ShowMoreArticlesButton',
                        _t('ElementArticles.ShowMoreArticlesButton', "Show 'more articles' button")
                    ),
                    TextField::create(
                        'ShowMoreArticlesButtonText',
                        _t('ElementArticles.ShowMoreArticlesButtonText', "Show 'more articles' button text")
                    )
                        ->displayIf('ShowMoreArticlesButton')
                        ->isChecked()
                        ->end(),
                ]
            );
        }

        return $fields;
    }

    public function getType(): string
    {
        return 'Articles list';
    }

    public function getArticles(): ?DataList
    {
        if ($this->ShowHighlightedArticlesOnly) {
            $articles = $this->ArticlesPage()->HighlightedArticles();
        } else {
            $articles = ArticlePage::get()->filter('ParentID', $this->ArticlesPage()->ID);
        }

        if ($articles) {
            $articles = $this->applyFilters($articles);
//            $articles = $this->applySorting($articles);
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
            $filterService->applyTypeFilter($this->Types()->column('Slug'));
        }

        $articles = $filterService->getArticles();

        if ($this->Authors()->count()) {
            $articles = $articles->filter('Author.Slug', $this->Authors()->column('Slug'));
        }

        return $articles;
    }

    private function applySorting(DataList $articles)
    {
        $pinnedArticles = $this->ArticlesPage()->PinnedArticles();
        $highlightedArticles = $this->ArticlesPage()->HighlightedArticles();

        $articles->sort('isHighlighted', $highlightedArticles);
    }
}
