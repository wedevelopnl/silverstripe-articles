<?php

namespace TheWebmen\Articles\Pages;

use SilverStripe\ORM\PaginatedList;

class CategoryPageController extends \PageController {

    /**
     * @return PaginatedList
     */
    public function PaginatedArticles()
    {
        $list = $this->Articles();
        $pagination = PaginatedList::create($list, $this->getRequest());
        $pagination->setPageLength($this->PageLength);
        if ($this->hasMethod('updatePaginatedArticles')) {
            $pagination = $this->updatePaginatedArticles($pagination);
        }
        return $pagination;
    }

}
