<?php

class m130726_165014_create_users_table extends Migration
{
	public function up()
	{
        $this->createTable('{{users}}', array(
            'id' => 'pk',
            'email' => 'string NOT NULL',
            'name' => 'string NOT NULL',
            'password' => 'string NOT NULL',
            'avatar' => 'string',
            'follower_count' => 'int NOT NULL DEFAULT 0',
            'following_count' => 'int NOT NULL DEFAULT 0',
            'notification_count' => 'int NOT NULL DEFAULT 0',
            'created_at' => 'int NOT NULL',
            'updated_at' => 'int NOT NULL',
        ));
	}

	public function down()
	{
        $this->dropTable('{{users}}');
		echo "m130726_165014_create_users_table does not support migration down.\n";
		return true;
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}
