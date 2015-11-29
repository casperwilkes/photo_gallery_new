<?php

namespace Fuel\Migrations;

class alter_field_username_in_users {

    public function up() {
        \DBUtil::modify_fields('users', array(
            'username' => array('name' => 'username', 'type' => 'varchar', 'constraint' => 50)
        ));
    }

    public function down() {
        \DBUtil::modify_fields('users', array(
            'username' => array('name' => 'username', 'constraint' => 255, 'type' => 'varchar'),
        ));
    }

}