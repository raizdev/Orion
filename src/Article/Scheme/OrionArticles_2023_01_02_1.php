<?php
namespace Orion\Article\Scheme;

use Phinx\Migration\AbstractMigration;

/**
 * Class AresArticles // do we have any other scheme file no i deleted it everywhere because it gave the error then i added it again but the error still shows up
 */ 
final class OrionArticles_2023_01_02_1 extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('ares_articles');
        $table->addColumn('title', 'string', ['limit' => 100])
            ->addColumn('slug', 'string', ['limit' => 100])
            ->addColumn('description', 'text')
            ->addColumn('content', 'text')
            ->addColumn('image', 'text')
            ->addColumn('thumbnail', 'text')
            ->addColumn('author_id', 'integer', ['limit' => 11])
            ->addColumn('cat_id', 'integer', ['limit' => 11])
            ->addColumn('hidden', 'integer', ['limit' => 11])
            ->addColumn('pinned', 'integer', ['limit' => 11])
            ->addColumn('likes', 'integer', ['limit' => 11])
            ->addColumn('dislikes', 'integer', ['limit' => 11])
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->addForeignKey('author_id', 'users', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
            ->addForeignKey('cat_id', 'ares_articles_categories', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
            ->create();
    }
}