<?php

namespace TheWebmen\Articles\Pages;

use SilverStripe\Security\Member;
use SilverStripe\Forms\DropdownField;

class AuthorPage extends \Page {

    private static $singular_name = 'Author';
    private static $plural_name = 'Authors';

    private static $show_in_sitetree = false;
    private static $allowed_children = [];

    /**
     * @var array
     */
    private static $has_one = array(
        'Author' => Member::class
    );

    /**
     * @return \SilverStripe\Forms\FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $allMembers = Member::get()->map()->toArray();
        $fields->addFieldToTab('Root.Main', DropdownField::create('AuthorID', _t(self::class . '.has_one_Author', 'Author'), $allMembers)->setHasEmptyDefault(true), 'Content');

        return $fields;
    }

}
