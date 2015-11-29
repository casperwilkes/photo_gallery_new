<?php

class Model_User extends \Orm\Model {

    protected static $_properties = array(
        'id',
        'username',
        'password',
        'group',
        'email',
        'last_login',
        'login_hash',
        'profile_fields',
        'created_at',
        'updated_at',
    );
    protected static $_has_many = array(
        'comments' => array(
            'key_from' => 'id',
            'model_to' => 'Model_Comment',
            'key_to' => 'user_id',
            'cascade_save' => true,
            'cascade_delete' => false
        ),
    );
    protected static $_observers = array(
        'Orm\Observer_CreatedAt' => array(
            'events' => array('before_insert'),
            'mysql_timestamp' => false,
        ),
        'Orm\Observer_UpdatedAt' => array(
            'events' => array('before_update'),
            'mysql_timestamp' => false,
        ),
    );
    protected static $_table_name = 'users';

    /**
     * Adds a validation object to the login form.
     * @return Validation
     */
    public static function validate_login() {
        $val = Validation::forge('login');
        $val->add('username', 'Username')
                ->add_rule('trim')
                ->add_rule('max_length', 255)
                ->add_rule('required');
        $val->add('password', 'Password')
                ->add_rule('trim')
                ->add_rule('max_length', 255)
                ->add_rule('required');
        return $val;
    }

    /**
     * Processes the login functionality
     * @param Validation $val The validation object
     * @param Auth $auth the user Auth object
     * @return boolean
     */
    public static function process_login(Validation $val, $auth) {
        try {
            $val->set_message('valid_email', 'Must use a valid email address');
            if ($val->run()) {
                $username = $val->validated('username');
                $password = $val->validated('password');
                if ($auth->login($username, $password)) {
                    return true;
                } else {
                    Session::set_flash('error', 'Incorrect username or password');
                    return false;
                }
            } else {
                Session::set_flash('error', $val->error());
                return false;
            }
        } catch (Exception $e) {
            Log::error($e, __METHOD__);
            Session::set_flash('error', 'Could not process login request at this time');
            return false;
        }
    }

    /**
     * Creates validation object for register form
     * @return Validation
     */
    public static function validate_register() {
        $val = Validation::forge('register');
        $val->add('username', 'Username')
                ->add_rule('min_length', 3)
                ->add_rule('max_length', 45)
                ->add_rule('required');
        $val->add('password', 'Choose Password', array('type' => 'password'))
                ->add_rule('min_length', 3)
                ->add_rule('max_length', 50)
                ->add_rule('required');
        $val->add('password2', 'Re-Type Password', array('type' => 'password'))
                ->add_rule('min_length', 3)
                ->add_rule('max_length', 50)
                ->add_rule('match_value', 'password', 1)
                ->add_rule('required');
        $val->add('email', 'E-Mail')
                ->add_rule('max_length', 255)
                ->add_rule('valid_email')
                ->add_rule('required');
        $val->set_message('required', ':label is a required field');
        $val->set_message('valid_email', 'A valid email is required');
        $val->set_message('match_value', 'Both passwords must match');
        return $val;
    }

    /**
     * Processes the registration form
     * @param Validation $val The validation form
     * @param Auth $auth The user's Auth object
     * @return boolean
     */
    public static function process_register(Validation $val, $auth) {
        try {
            if ($val->run()) {
                $username = $val->validated('username');
                $password = $val->validated('password');
                $email = $val->validated('email');
                $group = 2;
                $profile_fields = array(
                    'bio' => '',
                    'image' => 'noimg.jpg',
                );
                $user = $auth->create_user($username, $password, $email, $group, $profile_fields);

                if (!$user) {
                    Session::set_flash('error', 'There was a problem registering. Please try again later.');
                    return false;
                } else {
                    $auth->login($username, $password);
                    Session::set_flash('success', 'Welcome ' . $username);
                    return true;
                }
            } else {
                Session::set_flash('error', $val->error());
                return false;
            }
        } catch (Exception $e) {
            Log::error($e, __METHOD__);
            Session::set_flash('error', 'Could not process registration request at this time');
            return false;
        }
    }

    /**
     * The admin edit form
     * @param Fieldset $form Form being built
     * @param Model_User $user The user being edited
     * @return Fieldset
     */
    public static function populate_edit(Fieldset $form, Model_User $user) {
        $form->add('group', 'Group', array('value' => $user->group, 'type' => 'select'))
                ->set_label('Group')
                ->set_options('0', 'Banned')
                ->set_options('1', 'Administrator')
                ->set_options('2', 'User')
                ->add_rule('numeric_between', 0, 2);
        $form->add('submit', '', array('type' => 'submit', 'value' => 'Edit User'));
        $form->add('cancel', '', array('type' => 'reset', 'value' => 'Cancel'));
        return $form;
    }

    /**
     * Saves the edited fields
     * @param Fieldset $form The edit form
     * @param Model_User $user User being edited
     * @return boolean
     */
    public static function save_edit(Fieldset $form, Model_User $user) {
        try {
            // Run validation on form //
            $val = $form->validation();

            if ($val->run()) {
                if (Auth::update_user(array('group' => $val->validated('group')), $user->username)) {
                    Session::set_flash('success', 'User updated succesfully');
                    return true;
                } else {
                    Session::set_flash('error', 'User was not updated succesfully');
                    return false;
                }
            } else {
                Session::set_flash('error', $val->error());
                return false;
            }
        } catch (Exception $e) {
            Log::error($e, __METHOD__);
            Session::set_flash('error', 'Could not process update request at this time');
            return false;
        }
    }

    /**
     * Deletes a user from the database.
     * @param int $id The id of the user to remove
     * @return boolean
     */
    public static function remove_user($id) {
        $user = self::find($id);
        $user_image = unserialize($user->profile_fields)['image'];
        try {
            if (Auth::delete_user($user->username)) {
                Model_Profile::image_cleanup($user_image);
                Session::set_flash('success', 'User was removed');
                return true;
            } else {
                Session::set_flash('error', 'User was not removed');
                return false;
            }
        } catch (Exception $e) {
            Log::error($e, __METHOD__);
            Session::set_flash('error', 'User could not be removed at this time');
            return false;
        }
    }

}
