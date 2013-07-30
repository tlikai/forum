<?php

class m130730_044717_rename_topics_table_follower_count_column extends CDbMigration
{
	public function up()
	{
        $this->renameColumn('{{topics}}', 'follower_count', 'follow_count');
	}

	public function down()
	{
        $this->renameColumn('{{topics}}', 'follow_count', 'follower_count');
		echo "m130730_044717_rename_topics_table_follower_count_column does not support migration down.\n";
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
