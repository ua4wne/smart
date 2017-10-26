<?php

use yii\db\Migration;

/**
 * Handles the creation of table `material`.
 */
class m171026_174648_create_material_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('material', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull()->unique(),
            'category_id' => $this->integer(11)->notNull(),
            'image' => $this->string(30)->defaultValue(null),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('material');
    }
}
