<?php

namespace TheWebmen\Articles\Pages;

use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\Forms\GridField\GridFieldAddExistingAutocompleter;
use SilverStripe\ORM\ManyManyList;
use SilverStripe\Security\Member;
use SilverStripe\Forms\DateField;
use SilverStripe\Forms\NumericField;

/**
 * Class CategoryPage
 * @package TheWebmen\Articles\Pages
 *
 * @property int $PageLength
 *
 * @method ManyManyList Articles()
 */
class CategoryPage extends \Page
{

    private static $icon_class = 'font-icon-p-articles';
    private static $table_name = 'TheWebmen_CategoryPage';

    private static $singular_name = 'Category';
    private static $plural_name = 'Categories';

    private static $show_in_sitetree = true;
    private static $allowed_children = [];

    /**
     * @var array
     */
    private static $db = array(
        'PageLength' => 'Int'
    );

    /**
     * @var array
     */
    private static $many_many = array(
        'Articles' => ArticlePage::class
    );

    /**
     * @return \SilverStripe\Forms\FieldList
     */
    public function getCMSFields()
    {
        $this->beforeUpdateCMSFields(function (FieldList $fields) {
            if ($this->exists()) {
                $fields->addFieldToTab('Root.Articles', NumericField::create('PageLength'));
                $articlesConfig = GridFieldConfig_RelationEditor::create();
                $searchList = ArticlePage::get()->filter('ParentID', $this->ParentID);
                $articlesConfig->getComponentByType(GridFieldAddExistingAutocompleter::class)->setSearchList($searchList);
                $fields->findOrMakeTab('Root.Articles', _t(self::class . '.ARTICLES', 'Articles'));
                $fields->addFieldToTab('Root.Articles', GridField::create('Articles', _t(self::class . '.ARTICLES', 'Articles'), $this->Articles(), $articlesConfig));
            }
        });

        return parent::getCMSFields();
    }

}
