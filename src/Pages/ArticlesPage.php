<?php

namespace TheWebmen\Articles\Pages;

use SilverStripe\Lumberjack\Model\Lumberjack;
use SilverStripe\Forms\NumericField;

/**
 * Class ArticlesPage
 * @package TheWebmen\Articles\Pages
 *
 * @property int $PageLength
 */
class ArticlesPage extends \Page {

    private static $table_name = 'TheWebmen_ArticlesPage';
    
    /**
     * @var array
     */
    private static $extensions = [
        Lumberjack::class,
    ];

    /**
     * @var array
     */
    private static $allowed_children = [
        ArticlePage::class,
        CategoryPage::class
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
        return _t(self::class . '.ARTICLES', 'Articles');
    }

}
