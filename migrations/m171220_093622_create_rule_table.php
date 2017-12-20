<?php

use yii\db\Migration;

/**
 * Handles the creation of table `rule`.
 */
class m171220_093622_create_rule_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('rule', [
            'id' => $this->primaryKey(),
            'option_id' => $this->integer()->notNull(),
            'condition' => $this->string(5)->notNull(),
            'val' => $this->float(5,2)->notNull(),
            'action' => $this->string(5)->notNull(),
            'text' => $this->text()->notNull(),
            'runtime' => $this->dateTime(),
            'step' => $this->integer()->notNull()->defaultValue(0),
            'expire' => $this->smallInteger()->notNull()->defaultValue(1),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('rule');
    }
}
