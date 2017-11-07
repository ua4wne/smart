<?php

use yii\db\Migration;

/**
 * Handles the creation of table `device_type`.
 */
class m171107_155439_create_device_type_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('device_type', [
            'id' => $this->primaryKey(),
            'name' => $this->string(30)->notNull(),
            'descr' => $this->text(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('device_type');
    }
}
