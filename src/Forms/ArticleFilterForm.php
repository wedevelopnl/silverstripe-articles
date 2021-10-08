<?php

namespace App\Forms;

use SilverStripe\Control\RequestHandler;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FormAction;
use SilverStripe\Forms\TextField;

class ArticleFilterForm extends Form
{
    public const QUERY_PARAM = 'q';

    public function __construct(RequestHandler $controller = null, $name = self::DEFAULT_NAME)
    {
        $fields = FieldList::create(
            TextField::create(self::QUERY_PARAM, 'Zoeken')
        );

        $actions = FieldList::create(
            FormAction::create('Filter', 'Zoeken')
        );

        parent::__construct($controller, $name, $fields, $actions);

        $this->setFormMethod('GET');
        $this->disableSecurityToken();
    }
}
