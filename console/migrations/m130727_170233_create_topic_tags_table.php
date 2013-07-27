<?php

class m130727_170233_create_topic_tags_table extends CDbMigration
{
	public function up()
	{
        $this->createTable('{{topic_tags}}', array(
            'id' => 'pk',
            'tag_id' => 'int NOT NULL',
            'topic_id' => 'int NOT NULL',
        ));
	}

	public function down()
	{
        $this->dropTable('{{topic_tags}}');
		echo "m130727_170233_create_topic_tags_table does not support migration down.\n";
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
