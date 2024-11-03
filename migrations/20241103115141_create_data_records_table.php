<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateDataRecordsTable extends AbstractMigration
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
    public function change()
    {
        $table = $this->table('data_records');
        $table->addColumn('code', 'string', ['limit' => 20])
              ->addColumn('name', 'string', ['limit' => 255])
              ->addColumn('level1', 'string', ['limit' => 255, 'null' => true])
              ->addColumn('level2', 'string', ['limit' => 255, 'null' => true])
              ->addColumn('level3', 'string', ['limit' => 255, 'null' => true])
              ->addColumn('price', 'decimal', ['precision' => 10, 'scale' => 2, 'null' => true])
              ->addColumn('price_sp', 'decimal', ['precision' => 10, 'scale' => 2, 'null' => true])
              ->addColumn('quantity', 'integer', ['null' => true])
              ->addColumn('propertyFields', 'text', ['null' => true])
              ->addColumn('jointPurchases', 'string', ['limit' => 255, 'null' => true])
              ->addColumn('unit', 'string', ['limit' => 50, 'null' => true])
              ->addColumn('image', 'string', ['limit' => 255, 'null' => true])
              ->addColumn('showOnMain', 'boolean', ['default' => false])
              ->addColumn('description', 'text', ['null' => true])
              ->create();
    }
}
