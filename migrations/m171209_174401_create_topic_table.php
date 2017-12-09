<?php

use yii\db\Migration;

/**
 * Handles the creation of table `topic`.
 */
class m171209_174401_create_topic_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('topic', [
            'id' => $this->primaryKey(),
            'option_id' => $this->integer()->notNull(),
            'name' => $this->string(100)->notNull(),
            'route' => $this->string(10)->notNull(),
            'payload' => $this->string(70)->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('topic');
    }
}
