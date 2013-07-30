<?php

class m130730_085554_add_bookmark_count_to_replies_table extends CDbMigration
{
	public function up()
	{
        $this->addColumn('{{replies}}', 'bookmark_count', 'int NOT NULL default 0');
	}

	public function down()
	{
        $this->dropColumn('{{replies}}', 'bookmark_count');
		echo "m130730_085554_add_bookmark_count_to_replies_table does not support migration down.\n";
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
