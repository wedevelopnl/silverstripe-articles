<?php

namespace TheWebmen\Articles\Extensions;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordViewer;
use SilverStripe\ORM\DataExtension;
use TheWebmen\Articles\Pages\ArticlePage;

class MemberExtension extends DataExtension {

    private static $has_many = array(
        'Articles' => ArticlePage::class
    );
    
    private static $belongs_to = array(
        'AuthorPage' => AuthorPage::class
    );

    /**
     * @param FieldList $fields
     */
    public function updateCMSFields(FieldList $fields)
    {
        $articlesField = $fields->dataFieldByName('Articles');
        if($articlesField){
            $articlesField->setConfig( GridFieldConfig_RecordViewer::create() );
        }
    }

}
