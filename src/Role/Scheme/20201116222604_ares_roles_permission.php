<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/**
 * Class AresRolesPermission
 */
final class AresRolesPermission extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('ares_roles_permission');

        $data = [];

        $table->addColumn('role_id', 'integer', ['limit' => 10])
            ->addColumn('permission_id', 'integer', ['limit' => 10])
            ->addColumn('created_at', 'datetime')
            ->addForeignKey('role_id', 'ares_roles', 'id', ['delete'=> 'CASCADE', 'update'=> 'RESTRICT'])
            ->addForeignKey('permission_id', 'ares_permissions', 'id', ['delete'=> 'CASCADE', 'update'=> 'RESTRICT'])
            ->insert($data)
            ->create();
    }
}
