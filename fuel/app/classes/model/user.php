<?php

class Model_User extends \Orm\Model {

    protected static $_properties = array(
        'id',
        'username',
        'password',
        'email',
        'last_login',
        'login_hash',
        'profile_fields',
        'created_at',
        'updated_at',
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

    public static function populate_login(Fieldset $form) {
        $form->add('username', 'Username')
                ->add_rule('required');
        $form->add('password', 'Password')
                ->set_attribute('type', 'password')
                ->add_rule('required');
        $form->add('submit', '', array('value' => 'Login'))
                ->set_attribute('type', 'submit');
        return $form;
    }

    public static function validate_login(Fieldset $form, $auth, $input) {
        $val = $form->validation();
        if ($val->run()) {
            if ($auth->login($input['username'], $input['password'])) {
                return array('errors' => false, 'username' => $auth->get_screen_name());
            } else {
                return array('errors' => true,
                    'message' => Html::ul(array('Username or password incorrect.', 'Please try again')));
            }
        } else {
            return array('errors' => true, 'message' => $val->show_errors());
        }
    }

    public static function populate_edit(Fieldset $form, $user) {
        $form->add('old_password', 'Old Password')
                ->set_attribute('type', 'password')
                ->add_rule('required_with', 'password1')
                ->add_rule('required_with', 'password2')
                ->add_rule('min_length', 3)
                ->add_rule('max_length', 15);
        $form->add('password1', 'New Password')
                ->set_attribute('type', 'password')
                ->add_rule('required_with', 'old_password')
                ->add_rule('required_with', 'password2')
                ->add_rule('min_length', 3)
                ->add_rule('max_length', 15);
        $form->add('password2', 'Re-type Password')
                ->set_attribute('type', 'password')
                ->add_rule('required_with', 'old_password')
                ->add_rule('required_with', 'password1')
                ->add_rule('min_length', 3)
                ->add_rule('max_length', 15);
        $form->add('email', 'Email', array('value' => $user->email))
                ->add_rule('valid_email');
        $form->add('submit', '', array('type' => 'submit', 'value' => 'Edit User'));
        return $form;
    }

    public static function validate_edit(Fieldset $form, $input, $user) {
        $old_password = trim($input['old_password']);
        $password1 = trim($input['password1']);
        $password2 = trim($input['password2']);
        $email = trim($input['email']);
        // Whether form changed or not //
        $form_change = false;
        // Change //
        $change = array('email' => false, 'password' => false, 'group' => false);
        // Run validation on form //
        $val = $form->validation();

        // If email is not empty, and is different than what user already has //
        if (strlen($email)) {
            // Set custom message //
            $val->set_message('valid_email', 'Please enter a valid email address.');
            $change['email'] = $form_change = true;
        }

        // If passwords have been entered //
        if (strlen($password1) or strlen($password2) or strlen($old_password)) {
            // Check password 1 and 2 are the same //
            $val->field('password1')
                    ->add_rule('match_value', $form->field('password2')->get_attribute('value'), 1);
            // Set custom messages //
            $val->set_message('match_value', 'New Password and Re-type Password must match');
            $val->set_message('min_length', ':label length must be over 3 characters');
            $val->set_message('max_length', ':label cannot exceed 10 characters');
            $val->set_message('required_with', ':label must be filled out');
            $change['password'] = $form_change = true;
        }

        // If the form has changed //
        if ($form_change) {
            // Run validation //
            if ($val->run()) {
                $update = array(); // fall through
                if ($change['password']) {
                    $update['old_password'] = $old_password;
                    $update['password'] = $password1;
                }

                if ($change['email']) {
                    $update['email'] = $email;
                }

                try {
                    $result = Auth::update_user($update, $user->username);
                    $error = '';
                } catch (Exception $e) {
                    Log::error($e, __METHOD__);
                    $result = false;
                    $error = $e->getMessage();
                }
                $errors = isset($error) ? null : (Html::ul(array($error)));
                return array('return' => $result, 'errors' => $errors);
            } else {
                return array('return' => FALSE, 'errors' => $val->show_errors());
            }
        }
    }

}
