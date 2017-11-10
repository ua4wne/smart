<?php

use yii\db\Migration;

/**
 * Handles the creation of table `tarif`.
 */
class m171107_151844_create_tarif_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('tarif', [
            'id' => $this->primaryKey(),
            'device_id' => $this->integer()->notNull(),
            'koeff' => $this->float()->notNull(),
            'unit' => $this->char(5)->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('tarif');
    }
}
