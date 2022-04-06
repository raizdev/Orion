<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/**
 * Class AresRolesUser
 */
final class AresRolesRank extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('ares_roles_rank');
        $table->addColumn('role_id', 'integer', ['limit' => 10])
            ->addColumn('rank_id', 'integer', ['limit' => 10])
            ->addColumn('created_at', 'datetime')
            ->addForeignKey('role_id', 'ares_roles', 'id', ['delete'=> 'CASCADE', 'update'=> 'RESTRICT'])
            ->addForeignKey('rank_id', 'permissions', 'id', ['delete'=> 'CASCADE', 'update'=> 'RESTRICT'])
            ->create();
    }
}