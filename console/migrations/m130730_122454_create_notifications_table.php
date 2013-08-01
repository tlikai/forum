<?php

class m130730_122454_create_notifications_table extends CDbMigration
{
	public function up()
	{
        $this->createTable('{{notifications}}', array(
            'id' => 'pk',
            'read' => 'tinyint NOT NULL DEFAULT 0',
            'inbox' => 'tinyint NOT NULL DEFAULT 0', // array(1 => 'follow', 2 => 'reply', 3 => 'mention')
            'user_id' => 'int NOT NULL',
            'sender_id' => 'int NOT NULL DEFAULT 0',
            'topic_id' => 'int NOT NULL DEFAULT 0',
            'reply_id' => 'int NOT NULL DEFAULT 0',
            'created_at' => 'int NOT NULL',
            'updated_at' => 'int NOT NULL',
        ));
	}

	public function down()
	{
        $this->dropTable('{{notifications}}');
		echo "m130730_122454_create_notifications_table does not support migration down.\n";
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
