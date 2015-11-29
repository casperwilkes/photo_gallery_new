<?php

namespace Fuel\Migrations;

class Rename_field_pforile_fields_to_profile_fields_in_users
{
	public function up()
	{
		\DBUtil::modify_fields('users', array(
			'pforile_fields' => array('name' => 'profile_fields', 'type' => 'varchar', 'constraint' => 255)
		));
	}

	public function down()
	{
	\DBUtil::modify_fields('users', array(
			'profile_fields' => array('name' => 'pforile_fields', 'type' => 'varchar', 'constraint' => 255)
		));
	}
}