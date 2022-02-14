<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class PostTagTable extends AbstractMigration
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
        if (! $this->hasTable('post_tag')) {
            $table = $this->table('post_tag');
            $table->addColumn('post_id', 'integer')
                  ->addColumn('tag_id', 'integer')
                  ->addIndex('post_id')
                  ->addIndex('tag_id')
                  ->addForeignKey('post_id', 'post', 'id')
                  ->addForeignKey('tag_id', 'tag', 'id')
                  ->create();
        }
    }
}
