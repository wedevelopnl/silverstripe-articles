<?php

namespace WeDevelop\Articles\Tasks;

use SilverStripe\Dev\BuildTask;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\DataObjectInterface;
use SilverStripe\PolyExecution\PolyOutput;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
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
    protected string $title = 'Migrate data after changing namespace';

    protected static string $commandName = 'migrate-deprecated';

    protected static string $description = 'Migrate data from (The)Webmen_ to WeDevelop_ tables';

    protected function execute(InputInterface $input, PolyOutput $output): int
    {
        $deprecatedMapping = [
            'pages' => [
                DeprecatedArticlesPage::class => ArticlesPage::class,
                DeprecatedArticleThemePage::class => ArticleThemePage::class,
                DeprecatedArticleTypePage::class => ArticleTypePage::class,
                DeprecatedArticlePage::class => ArticlePage::class,
            ],
            'models' => [
                DeprecatedAuthor::class => Author::class,
                DeprecatedTag::class => Tag::class,
            ],
        ];

        /**
         * @var  DataObjectInterface $oldModel
         * @var  DataObjectInterface $newClass
         */
        foreach ($deprecatedMapping['models'] as $oldModel => $newModel) {
            print("Move data from $oldModel to $newModel \n");

            $oldObjects = $oldModel::get();
            $ct = 0;
            /** @var DataObject $oldObject */
            foreach ($oldObjects as $oldObject) {
                $newObject = $oldObject->newClassInstance($newModel);
                $newObject->write();
                $ct++;
            }
            print("{$ct} records updated.\n");
        }

        return Command::SUCCESS;
    }
}
