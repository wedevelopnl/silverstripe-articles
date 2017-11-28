<?php

namespace TheWebmen\Articles\Pages;

use SilverStripe\Forms\NumericField;
use SilverStripe\Lumberjack\Model\Lumberjack;

class AuthorsPage extends \Page {

    private static $extensions = [
        Lumberjack::class,
    ];

    private static $allowed_children = [
        AuthorPage::class
    ];
    private static $default_child = AuthorPage::class;

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
        return _t(self::class . '.AUTHORS', 'Authors');
    }

}
