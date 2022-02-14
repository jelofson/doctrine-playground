<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class PostTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        if (! $this->hasTable('post')) {
            $table = $this->table('post');
            $table->addColumn('post', 'text')
                  ->addColumn('user_id', 'integer')
                  ->addColumn('created', 'timestamp')
                  ->addColumn('updated', 'timestamp')
                  ->addIndex('user_id')
                  ->addForeignKey('user_id', 'user', 'id')
                  ->create();
        }
    }
}
