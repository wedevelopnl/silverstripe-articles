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

/***
 * https://localhost:17080/new-article-overview-page/?thema=thema-1,thema-2&type=
 * https://localhost:17080/new-article-overview-page/?thema%5B%5D=thema-1&thema%5B%5D=thema-2&type=
 */
