<?php

namespace TheWebmen\Articles\Pages;

class AuthorPageController extends \PageController {

    /**
     * Init
     */
    public function init(){
        parent::init();
        $author = $this->Author();
        if(!$author){
            $this->httpError(404);
        }
    }

}
