<?php

namespace Fuel\Migrations;

class Rename_field_author_to_user_id_in_comments
{
	public function up()
	{
		\DBUtil::modify_fields('comments', array(
			'author' => array('name' => 'user_id', 'type' => 'varchar', 'constraint' => 255)
		));
	}

	public function down()
	{
	\DBUtil::modify_fields('comments', array(
			'user_id' => array('name' => 'author', 'type' => 'varchar', 'constraint' => 255)
		));
	}
}