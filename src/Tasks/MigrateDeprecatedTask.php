<?php

namespace WeDevelop\Articles\Tasks;

use SilverStripe\Dev\BuildTask;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\DataObjectInterface;
use WeDevelop\Articles\Models\Author;
use WeDevelop\Articles\Models\DeprecatedAuthor;
use WeDevelop\Articles\Models\DeprecatedTag;
use WeDevelop\Articles\Models\Tag;
use WeDevelop\Articles\Pages\ArticlePage;
use WeDevelop\Articles\Pages\ArticlesPage;
use WeDevelop\Articles\Pages\ArticleThemePage;
use WeDevelop\Articles\Pages\ArticleTypePage;
use WeDevelop\Articles\Pages\DeprecatedArticlePage;
use WeDevelop\Articles\Pages\DeprecatedArticlesPage;
use WeDevelop\Articles\Pages\DeprecatedArticleThemePage;
use WeDevelop\Articles\Pages\DeprecatedArticleTypePage;

class MigrateDeprecatedTask extends BuildTask
{
    protected $title = 'Migrate data after changing namespace';

    private static string $segment = 'migrate-deprecated';

    protected $description = 'Migrate data from (The)Webmen_ to WeDevelop_ tables';

    public function run($request)
    {
        $deprecatedMapping = [
            DeprecatedAuthor::class => Author::class,
            DeprecatedTag::class => Tag::class,
            DeprecatedArticlePage::class => ArticlePage::class,
            DeprecatedArticlesPage::class => ArticlesPage::class,
            DeprecatedArticleThemePage::class => ArticleThemePage::class,
            DeprecatedArticleTypePage::class => ArticleTypePage::class,
        ];

        /**
         * @var  DataObjectInterface $oldClass
         * @var  DataObjectInterface $newClass
         */
        foreach ($deprecatedMapping as $oldClass => $newClass) {
            print("Move data from $oldClass to $newClass \n");

            $oldObjects = $oldClass::get();
            $ct = 0;
            /** @var DataObject $oldObject */
            foreach ($oldObjects as $oldObject) {
                $newObject = $oldObject->newClassInstance($newClass);
                $newObject->write();
                $ct++;
            }
            print("{$ct} records updated.\n");
        }
    }
}
