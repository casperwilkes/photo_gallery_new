<?php

class Controller_Users extends Controller_Template {

    public function action_login() {
        $auth = Auth::instance();
        if ($auth->check()) {
            Response::redirect('admin/');
        }
        $view = View::forge('users/login');
        if (Input::post()) {
            if (Model_User::process_login(Model_User::validate_login(), $auth)) {
                if ($auth->get('group', 2) == 0) {
                    $auth->logout();
                    Session::set_flash('error', 'This user has been banned. If you disagree with this decision please contact the administrator.');
                } else {
                    Session::set_flash('success', 'Welcome back ' . $auth->get('username'));
                    if ($auth->get('group', 2) == 1) {
                        Response::redirect('admin');
                    } else {
                        Response::redirect('photographs');
                    }
                }
            }
        }
        $this->template->title = 'Admin &raquo; Login';
        $this->template->content = $view;
    }

    public function action_logout() {
        $auth = Auth::instance();
        if ($auth->check()) {
            $auth->logout();
            Session::set_flash('success', 'User successfully logged out.');
        } else {
            Session::set_flash('error', 'User could not be logged out at this time.');
        }
        Response::redirect('photographs/');
    }

    public function action_register() {
        if (Input::post()) {
            if (Model_User::process_register(Model_User::validate_register(), Auth::instance())) {
                Response::redirect('photographs');
            }
        }

        $this->template->title = 'Register User';
        $this->template->content = View::forge('users/register');
    }

}
