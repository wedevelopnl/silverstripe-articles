<?php

namespace WeDevelop\Articles;

use SilverStripe\Control\RequestHandler;
use SilverStripe\Forms\CheckboxSetField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FormAction;
use SilverStripe\ORM\FieldType\DBHTMLText;
use SilverStripe\View\Requirements;
use WeDevelop\Articles\Pages\ArticleThemePage;
use WeDevelop\Articles\Pages\ArticleTypePage;

class ArticleFilterForm extends Form
{
    public function __construct(RequestHandler $controller = null, $name = self::DEFAULT_NAME)
    {
        $fields = new FieldList();

        if (!$controller->data() instanceof ArticleTypePage) {
            $fields->push(
                DropdownField::create(
                    'type',
                    _t('WeDevelop\Articles\Pages\ArticleTypePage.SINGULARNAME', 'Type'),
                    $controller->getTypes()->map('URLSegment', 'Title')->toArray()
                )->setHasEmptyDefault(true)->setEmptyString('Choose a type')
            );
        }

        if (!$controller->data() instanceof ArticleThemePage) {
            $fields->push(
                CheckboxSetField::create(
                    'thema',
                    _t('WeDevelop\Articles\Pages\ArticleThemePage.SINGULARNAME', 'Theme'),
                    $controller->getThemes()->map('URLSegment', 'Title')->toArray()
                )
            );
        }

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

    /**
     * @return DBHTMLText
     */
    public function forTemplate()
    {
        Requirements::javascript('WeDevelop/silverstripe-articles:client/dist/formURLHandler.js');

        return parent::forTemplate();
    }
}
