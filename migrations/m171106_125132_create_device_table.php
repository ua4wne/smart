<?php

use yii\db\Migration;

/**
 * Handles the creation of table `device`.
 */
class m171106_125132_create_device_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('device', [
            'id' => $this->primaryKey(),
            'uid' => $this->char(16)->notNull()->unique(),
            'name' => $this->string(70)->notNull(),
            'descr' => $this->text(),
            'address' => $this->string(32),
            'verify' => $this->smallInteger()->notNull()->defaultValue(0),
            'protocol_id' => $this->integer(),
            'location_id' => $this->integer(),
            'image' => $this->string(50)->defaultValue(null),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('device');
    }
}
