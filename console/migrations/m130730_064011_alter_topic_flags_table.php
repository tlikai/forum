<?php

class m130730_064011_alter_topic_flags_table extends CDbMigration
{
	public function up()
	{
        $this->renameTable('{{topic_flags}}', '{{user_actions}}');
        $this->addColumn('{{user_actions}}', 'reply_id', 'int NOT NULL DEFAULT 0');
	}

	public function down()
	{
        $this->dropColumn('{{user_actions}}', 'reply_id');
        $this->renameTable('{{user_actions}}', '{{topic_flags}}');
		echo "m130730_064011_alter_topic_flags_table does not support migration down.\n";
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
