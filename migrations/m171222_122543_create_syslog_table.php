<?php

use yii\db\Migration;

/**
 * Handles the creation of table `syslog`.
 */
class m171222_122543_create_syslog_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('syslog', [
            'id' => $this->primaryKey(),
            'type' => $this->string(7)->notNull(),
            'msg' => $this->text()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('syslog');
    }
}
