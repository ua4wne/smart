<?php

use yii\db\Migration;

/**
 * Handles the creation of table `protocol`.
 */
class m190128_163320_create_protocol_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('protocol', [
            'id' => $this->primaryKey(),
            'code' => $this->string(7)->unique()->notNull(),
            'name' => $this->string(30)->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ],$tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('protocol');
    }
}
