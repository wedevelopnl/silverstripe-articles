<?php

namespace TheWebmen\Articles\Pages;

use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\Forms\GridField\GridFieldAddExistingAutocompleter;
use SilverStripe\Security\Member;
use SilverStripe\Forms\DateField;

class ArticlePage extends \Page
{
    
    private static $table_name = 'TheWebmen_ArticlePage';

    private static $singular_name = 'Article';
    private static $plural_name = 'Articles';

    private static $show_in_sitetree = false;
    private static $allowed_children = [];

    /**
     * @var array
     */
    private static $db = [
        'Date' => 'DBDatetime'
    ];
    
    /**
     * @var array
     */
    private static $has_one = array(
        'Author' => Member::class
    );

    /**
     * @var array
     */
    private static $many_many = array(
        'RelatedArticles' => ArticlePage::class
    );

    /**
     * @return \SilverStripe\Forms\FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $allMembers = Member::get()->map()->toArray();
        $fields->addFieldToTab('Root.Main', DropdownField::create('AuthorID', 'Author', $allMembers)->setHasEmptyDefault(true), 'Content');
        $fields->addFieldToTab('Root.Main', DateField::create('Date', 'Date'), 'Content');
        
        if ($this->exists()) {
            $relatedConfig = GridFieldConfig_RelationEditor::create();
            $searchList = ArticlePage::get()->filter('ParentID', $this->ParentID)->exclude('ID', $this->ID);
            $relatedConfig->getComponentByType(GridFieldAddExistingAutocompleter::class)->setSearchList($searchList);
            $fields->findOrMakeTab('Root.Related', _t(self::class . '.RELATED', 'Related'));
            $fields->addFieldToTab('Root.Related', GridField::create('RelatedArticles', _t(self::class . '.RELATED', 'Related'), $this->RelatedArticles(), $relatedConfig));
        }

        return $fields;
    }

    /**
     * @return string/bool
     */
    public function AuthorName(){
        $author = $this->Author();
        if($author){
            return $author->getName();
        }
        return false;
    }

}
