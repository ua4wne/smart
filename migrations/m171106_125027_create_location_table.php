<?php

use yii\db\Migration;

/**
 * Handles the creation of table `location`.
 */
class m171106_125027_create_location_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('location', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('location');
    }
}
