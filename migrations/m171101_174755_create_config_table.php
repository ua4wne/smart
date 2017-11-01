<?php

use yii\db\Migration;

/**
 * Handles the creation of table `config`.
 */
class m171101_174755_create_config_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('config', [
            'id' => $this->primaryKey(),
            'param' => $this->string(100)->notNull()->unique(),
            'val' => $this->string(70)->notNull(),
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
        $this->dropTable('config');
    }
}
