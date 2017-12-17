<?php

use yii\db\Migration;

/**
 * Handles the creation of table `logger`.
 */
class m171217_174253_create_logger_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('logger', [
            'id' => $this->primaryKey(),
            'option_id' => $this->integer()->notNull(),
            'val' => $this->float(5,2)->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('logger');
    }
}
