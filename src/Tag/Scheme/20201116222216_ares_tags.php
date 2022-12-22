<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/**
 * Class AresCompetitions
 */
final class AresCompetitions extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('ares_tags');
        $table->addColumn('user_id', 'integer', ['limit' => 10])
            ->addColumn('tag', 'string', ['limit' => 10])
            ->addColumn('created_at', 'datetime')
            ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'CASCADE', 'update'=> 'RESTRICT'])
            ->create();
    }
}
