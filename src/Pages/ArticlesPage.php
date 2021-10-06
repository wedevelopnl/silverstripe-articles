<?php

namespace Webmen\Articles\Pages;

use SilverStripe\Lumberjack\Model\Lumberjack;
use SilverStripe\Forms\NumericField;

/**
 * Class ArticlesPage
 * @package Webmen\Articles\Pages
 *
 * @property int $PageLength
 */
class ArticlesPage extends \Page {

    /***
     * @var string
     */
    private static $table_name = 'Webmen_ArticlesPage';

    /***
     * @var string
     */
    private static $singular_name = 'Articles overview page';

    /***
     * @var string
     */
    private static $plural_name = 'Articles overview pages';

    /***
     * @var string
     */
    private static $icon_class = 'font-icon-p-article';

    /**
     * @var array
     */
    private static $allowed_children = [
        ArticlePage::class,
        ThemePage::class
    ];

    private static $default_child = ArticlePage::class;

    /**
     * @var array
     */
    private static $db = array(
        'PageLength' => 'Int'
    );

    /**
     * @return \SilverStripe\Forms\FieldList
     */
    public function getCMSFields(){
        $fields = parent::getCMSFields();

        $childPagesField = $fields->dataFieldByName('ChildPages');
        if($childPagesField){
            $childPagesField->setTitle('');
            $fields->addFieldToTab('Root.ChildPages', NumericField::create('PageLength'));
        }

        return $fields;
    }

    /**
     * @return string
     */
    public function getLumberjackTitle(){
        return _t(self::class . '.ARTICLES', 'Articlesf');
    }

}
