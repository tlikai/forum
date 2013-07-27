<?php

class m130727_155304_create_topics_table extends CDbMigration
{
	public function up()
	{
        $this->createTable('{{topics}}', array(
            'id' => 'pk',
            'subject' => 'string NOT NULL',
            'content' => 'text NOT NULL',
            'created_by' => 'int NOT NULL',
            'last_post_at' => 'int NOT NULL',
            'last_post_by' => 'int NOT NULL',
            'score' => 'int NOT NULL DEFAULT 0',
            'like_count' => 'int NOT NULL DEFAULT 0',
            'reply_count' => 'int NOT NULL DEFAULT 0',
            'follower_count' => 'int NOT NULL DEFAULT 0',
            'created_at' => 'int NOT NULL DEFAULT 0',
            'updated_at' => 'int NOT NULL DEFAULT 0',
        ));
	}

	public function down()
	{
        $this->dropTable('{{topics}}');
		echo "m130727_155304_create_topics_table does not support migration down.\n";
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
