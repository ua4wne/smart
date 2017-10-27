<?php

use yii\db\Migration;

/**
 * Handles the creation of table `stock`.
 */
class m171027_103638_create_stock_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('stock', [
            'id' => $this->primaryKey(),
            'cell_id' => $this->integer()->notNull(),
            'material_id' => $this->integer()->notNull(),
            'quantity' => $this->integer()->notNull(),
            'unit_id' => $this->integer()->notNull(),
            'price' => $this->decimal(8,2)->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('stock');
    }
}
