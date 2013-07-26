<?php

class m130726_185945_create_tags_table extends CDbMigration
{
	public function up()
	{
        $this->createTable('{{tags}}', array(
            'id' => 'pk',
            'name' => 'string NOT NULL',
            'title' => 'string NOT NULL',
        ));
	}

	public function down()
	{
        $this->dropTable('{{tags}}');
		echo "m130726_185945_create_tags_table does not support migration down.\n";
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
