<?php

namespace TheWebmen\Articles\GridFieldActions;

use SilverStripe\ORM\DB;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldAddExistingAutocompleter;
use TheWebmen\Articles\Pages\ArticlePage;

class ArticlesGridFieldAddExistingAutocompleter extends GridFieldAddExistingAutocompleter
{
    public function handleAction(GridField $gridField, $actionName, $arguments, $data)
    {
        if ($fieldToUpdate = $this->getFieldToUpdate($gridField)) {
            $IDToUpdate = $data['relationID'];

            // Safety check for possible injection
            if (!is_numeric($IDToUpdate)) {
                return parent::handleAction($gridField, $actionName, $arguments, $data);
            }

            $item = ArticlePage::get_by_id($IDToUpdate);

            if (!$item) {
                return parent::handleAction($gridField, $actionName, $arguments, $data);
            }

            DB::query("UPDATE TheWebmen_ArticlePage SET $fieldToUpdate = 1 WHERE ID = $IDToUpdate");

            if ($item->isPublished()) {
                DB::query("UPDATE TheWebmen_ArticlePage_Live SET $fieldToUpdate = 1 WHERE ID = $IDToUpdate");
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
