<?php

class m130729_183702_create_topic_flags_table extends CDbMigration
{
	public function up()
	{
        $this->createTable('{{topic_flags}}', array(
            'id' => 'pk',
            'flag' => 'tinyint NOT NULL', // array(1 => 'reply', 2 => 'like', 3 => 'follow')
            'user_id' => 'int NOT NULL',
            'topic_id' => 'int NOT NULL',
        ));
	}

	public function down()
	{
        $this->dropTable('{{topic_flags}}');
		echo "m130729_183702_create_topic_flags_table does not support migration down.\n";
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
