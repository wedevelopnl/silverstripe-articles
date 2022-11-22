<?php

namespace WeDevelop\Articles\ElementalGrid;

use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\TreeDropdownField;
use WeDevelop\Articles\Pages\ArticlePage;

/**
 * Class ElementArticle
 * @package WeDevelop\Articles\ElementalGrid
 *
 * @method ArticlePage ArticlePage()
 */
class ElementArticle extends BaseElement
{
    /**
     * @var string
     */
    private static $table_name = 'Element_Article';

    /**
     * @var string
     */
    private static $singular_name = 'Article';

    /**
     * @var string
     */
    private static $plural_name = 'Articles';

    /**
     * @var string
     */
    private static $description = 'Show an article in a grid element';

    /**
     * @var string
     */
    private static $icon = 'font-icon-p-list';

    /**
     * @var array
     */
    private static $has_one = [
        'ArticlePage' => ArticlePage::class,
    ];

    /**
     * @var array
     */
    private static $db = [
        'ShowMoreArticlesButton' => 'Boolean',
        'ShowMoreArticlesButtonText' => 'Varchar(255)',
    ];

    public function getCMSFields(): FieldList
    {
        $fields = parent::getCMSFields();

        $fields->addFieldsToTab(
            'Root.Main',
            [
                TreeDropdownField::create('ArticlePageID', _t(__CLASS__ . '.ARTICLETOSHOW', 'Article to show'), ArticlePage::class),
            ]
        );

        $fields->removeByName(
            [
                'ShowMoreArticlesButton',
                'ShowMoreArticlesButtonText',
            ]
        );

        $fields->addFieldsToTab(
            'Root.Main',
            [
                CheckboxField::create(
                    'ShowMoreArticlesButton',
                    _t('WeDevelop\Articles\ElementalGrid.SHOWMOREBUTTON', "Show 'more articles' button")
                ),
                TextField::create(
                    'ShowMoreArticlesButtonText',
                    _t('WeDevelop\Articles\ElementalGrid.SHOWMOREBUTTONTEXT', "Show 'more articles' button")
                )
                    ->displayIf('ShowMoreArticlesButton')
                    ->isChecked()
                    ->end(),
            ]
        );

        $this->extend('onAfterUpdateCMSFields', $fields);

        return $fields;
    }

    public function getType(): string
    {
        return 'Article';
    }
}
