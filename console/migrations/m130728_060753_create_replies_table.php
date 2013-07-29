<?php

class m130728_060753_create_replies_table extends CDbMigration
{
	public function up()
	{
        $this->createTable('{{replies}}', array(
            'id' => 'pk',
            'content' => 'text NOT NULL',
            'topic_id' => 'int NOT NULL',
            'created_by' => 'int NOT NULL',
            'like_count' => 'int NOT NULL DEFAULT 0',
            'created_at' => 'int NOT NULL DEFAULT 0',
            'updated_at' => 'int NOT NULL DEFAULT 0',
        ));
	}

	public function down()
	{
        $this->dropTable('{{replies}}');
		echo "m130728_060753_create_replies_table does not support migration down.\n";
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
