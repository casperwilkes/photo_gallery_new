<?php

namespace Fuel\Migrations;

class Create_comments
{
	public function up()
	{
		\DBUtil::create_table('comments', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'photograph_id' => array('constraint' => 11, 'type' => 'int'),
			'author' => array('constraint' => 255, 'type' => 'varchar'),
			'body' => array('type' => 'text'),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('comments');
	}
}