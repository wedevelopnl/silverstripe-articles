<?php

namespace TheWebmen\Articles\GridFieldActions;

use SilverStripe\ORM\DB;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldDeleteAction;

class ArticlesGridFieldDeleteAction extends GridFieldDeleteAction
{
    public function __construct(bool $removeRelation = true)
    {
        parent::__construct($removeRelation);
    }

    public function handleAction(GridField $gridField, $actionName, $arguments, $data)
    {
        if ($fieldToUpdate = $this->getFieldToUpdate($gridField)) {
            $IDToUpdate = $arguments['RecordID'];

            // Safety check for possible injection
            if (!is_numeric($IDToUpdate)) {
                return parent::handleAction($gridField, $actionName, $arguments, $data);
            }

            /** @var ArticlePage $item */
            $item = $gridField->getList()->byID($IDToUpdate);

            DB::query("UPDATE TheWebmen_ArticlePage SET $fieldToUpdate = 0 WHERE ID = $IDToUpdate");

            if ($item->isPublished()) {
                DB::query("UPDATE TheWebmen_ArticlePage_Live SET $fieldToUpdate = 0 WHERE ID = $IDToUpdate");
            }
        }

        parent::handleAction($gridField, $actionName, $arguments, $data);
    }

    private function getFieldToUpdate(GridField $gridField): ?string
    {
        switch ($gridField->getList()->getJoinTable()) {
            case 'TheWebmen_ArticlesPage_PinnedArticles':
                return 'Pinned';

            case 'TheWebmen_ArticlesPage_HighlightedArticles':
                return 'Highlighted';

            default:
                return null;
        }
    }
}
