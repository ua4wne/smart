<?php

use yii\db\Migration;

/**
 * Handles the creation of table `option`.
 */
class m171117_132711_create_option_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('option', [
            'id' => $this->primaryKey(),
            'device_id' => $this->integer()->notNull(),
            'val' => $this->float(5,2)->notNull(),
            'unit' => $this->string(7)->notNull(),
            'alias' => $this->string(50),
            'to_log' => $this->smallInteger(4)->notNull()->defaultValue(0),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('option');
    }
}
