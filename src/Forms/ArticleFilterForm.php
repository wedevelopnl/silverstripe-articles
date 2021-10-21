<?php

namespace TheWebmen\Articles;

use SilverStripe\Control\RequestHandler;
use SilverStripe\Forms\CheckboxSetField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FormAction;
use SilverStripe\Forms\Validator;
use SilverStripe\View\Requirements;

class ArticleFilterForm extends Form
{
    public function __construct(RequestHandler $controller = null, $name = self::DEFAULT_NAME)
    {
        $fields = new FieldList(
            CheckboxSetField::create(
                'thema',
                _t('Theme.Singular', 'Theme'),
                $controller->getThemes()->map('URLSegment', 'Title')->toArray()
            ),
            DropdownField::create(
                'type',
                _t('Type.Singular', 'Type'),
                $controller->getTypes()->map('Slug', 'Title')->toArray()
            )->setHasEmptyDefault(true)->setEmptyString('Choose a type')
        );

        $actions = new FieldList(
            FormAction::create('doArticleFilterForm', 'Filter')
                ->setName('')
        );

        parent::__construct($controller, '', $fields, $actions);

        $formdata = [];

        foreach ($controller->getRequest()->getVars() as $key => $value) {
            if (strpos($value, ',') !== false) {
                $formdata[$key] = explode(',', $value);
            } else {
                $formdata[$key] = $value;
            }
        }

        $this->loadDataFrom($formdata);
        $this->setFormMethod('GET');
        $this->disableSecurityToken();
    }

    public function forTemplate()
    {
        Requirements::javascript('thewebmen/silverstripe-articles:client/dist/formURLHandler.js');

        return parent::forTemplate();
    }
}
