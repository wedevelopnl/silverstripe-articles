<?php

namespace TheWebmen\Articles\Pages;

use SilverStripe\ORM\PaginatedList;

class AuthorsPageController extends \PageController {

    /**
     * @return AuthorPage
     */
    public function AllAuthors(){
        return AuthorPage::get()->filter('ParentID', $this->ID);
    }

    /**
     * @return PaginatedList
     */
    public function PaginatedAuthors()
    {
        $list = $this->AllAuthors();
        $pagination = PaginatedList::create($list, $this->getRequest());
        $pagination->setPageLength($this->PageLength);
        return $pagination;
    }

}
