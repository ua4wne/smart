<?php

use yii\db\Migration;

/**
 * Handles the creation of table `counter_log`.
 */
class m171107_151730_create_counter_log_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('counter_log', [
            'id' => $this->primaryKey(),
            'device_id' => $this->integer()->notNull(),
            '_year' => $this->char(4)->notNull(),
            '_month' => $this->char(2)->notNull(),
            'val' => $this->float(2)->notNull(),
            'koeff' => $this->float(2)->notNull(),
            'price' => $this->decimal(2)->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('counter_log');
    }
}
