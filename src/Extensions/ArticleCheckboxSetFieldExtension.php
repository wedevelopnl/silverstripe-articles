<?php

namespace TheWebmen\Articles\Extensions;

use SilverStripe\Core\Extension;
use SilverStripe\ORM\ArrayList;
use SilverStripe\View\ArrayData;

class ArticleCheckboxSetFieldExtension extends Extension
{
    /***
     * @param ArrayList $options
     */
    public function updateGetOptions(&$options)
    {
        /** @var ArrayData $option */
        foreach ($options as $option) {
            $option->setField('Name', "{$this->owner->name}[]");
        }
    }
}
