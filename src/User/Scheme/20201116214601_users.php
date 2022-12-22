<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/**
 * Class Users
 */
final class Users extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('users');

        $created_at = $table->hasColumn('created_at');
        $updated_at = $table->hasColumn('updated_at');
        $real_name  = $table->hasColumn('real_name');

        $table->changeColumn('password', 'string', ['limit' => 255])
            ->save();

        $table->addColumn('avatar_bg', 'integer', ['default' => '1']);

        if ($real_name) {
            $table->changeColumn('real_name', 'string', ['limit' => 25, 'null' => true])
                ->save();
        }
        if (!$created_at && !$updated_at) {
            $table->addColumn('created_at', 'datetime')
                ->addColumn('updated_at', 'datetime', ['null' => true])
                ->save();
        }
    }
}
