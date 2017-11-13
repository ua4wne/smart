<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user`.
 */
class m171025_170755_create_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'username' => $this->string(30)->notNull()->unique(),
            'fname' => $this->string(50)->notNull(),
            'lname' => $this->string(50)->notNull(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string(40)->notNull()->unique(),
            'phone' => $this->string(12)->notNull()->unique(),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
            'role_id' => $this->integer(11)->notNull(),
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
        $this->dropTable('user');
    }
}
