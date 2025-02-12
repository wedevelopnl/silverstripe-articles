<?php

namespace WeDevelop\Articles\Tasks;

use App\Pages\Articles\PressArticlePage;
use SilverStripe\Core\ClassInfo;
use SilverStripe\Dev\BuildTask;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\Queries\SQLSelect;
use WeDevelop\Articles\Pages\ArticlePage;

class MigrateAuthorTask extends BuildTask
{
    protected $title = 'Migrate Author to ArticleAuthor';

    private static string $segment = 'migrate-author';

    protected $description = 'Migrate Author field to ArticleAuthor';

    public function run($request)
    {
        $subclasses = ClassInfo::subclassesFor(ArticlePage::class);

        $articles = ArticlePage::get();

        $authorIds = $this->getArticleAuthorIDs();

        foreach ($articles as $article) {
            $articleAuthorId = $authorIds[$article->ID] ?? 0;

            echo "Setting ArticleAuthord id {$articleAuthorId} for article {$article->Title}". PHP_EOL;

            $article->ArticleAuthorID = $articleAuthorId;
            $article->write();
        }
        echo "Migration complete!";
    }

    private function getArticleAuthorIDs(): array
    {
        $tableName = DataObject::getSchema()->tableName(ArticlePage::class);

        $query = SQLSelect::create()->setFrom($tableName)->setSelect(['ID', 'AuthorID']);
        $records = iterator_to_array($query->execute());

        return array_combine(
            array_column($records, 'ID'),
            array_column($records, 'AuthorID')
        );
    }
}
