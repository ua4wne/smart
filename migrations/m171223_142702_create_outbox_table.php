<?php

use yii\db\Migration;

/**
 * Handles the creation of table `outbox`.
 */
class m171223_142702_create_outbox_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('outbox', [
            'id' => $this->primaryKey(),
            'from' => $this->string(30)->notNull(),
            'to' => $this->string(30)->notNull(),
            'msg' => $this->text()->notNull(),
            'is_new' => $this->smallInteger(4)->notNull()->defaultValue(1),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('outbox');
    }
}
