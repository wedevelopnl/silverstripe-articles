<?php

namespace WeDevelop\Articles\GridFieldActions;

use SilverStripe\ORM\DB;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldAddExistingAutocompleter;
use WeDevelop\Articles\Pages\ArticlePage;

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

            DB::query("UPDATE WeDevelop_ArticlePage SET $fieldToUpdate = 1 WHERE ID = $IDToUpdate");

            if ($item->isPublished()) {
                DB::query("UPDATE WeDevelop_ArticlePage_Live SET $fieldToUpdate = 1 WHERE ID = $IDToUpdate");
            }
        }

        parent::handleAction($gridField, $actionName, $arguments, $data);
    }

    private function getFieldToUpdate(GridField $gridField): ?string
    {
        switch ($gridField->getList()->getJoinTable()) {
            case 'WeDevelop_ArticlesPage_PinnedArticles':
                return 'Pinned';

            case 'WeDevelop_ArticlesPage_HighlightedArticles':
                return 'Highlighted';

            default:
                return null;
        }
    }
}
