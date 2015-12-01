<?php

class Controller_Users extends Controller_Template {

    public $template = 'template.twig';
    private $auth = '';

    public function before() {
        parent::before();
        $this->auth = Auth::instance();
        $this->template->auth = $this->auth;
    }

    public function action_login() {

        if ($this->auth->check()) {
            Response::redirect('admin/');
        }

        $view = View::forge('users/login.twig');

        if (Input::post()) {
            if (Model_User::process_login(Model_User::validate_login(), $this->auth)) {
                if ($this->auth->get('group', 2) == 0) {
                    $this->auth->logout();
                    Session::set_flash('error', 'This user has been banned. If you disagree with this decision please contact the administrator.');
                } else {
                    Session::set_flash('success', 'Welcome back ' . $this->auth->get('username'));
                    if ($this->auth->get('group', 2) == 1) {
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
        $name = $this->auth->get_screen_name();
        if ($this->auth->check()) {
            $this->auth->logout();
            Session::set_flash('success', $name . ' was successfully logged out.');
        } else {
            Session::set_flash('error', $name . ' could not be logged out at this time.');
        }
        Response::redirect('photographs/');
    }

    public function action_register() {
        if (Input::post()) {
            if (Model_User::process_register(Model_User::validate_register(), $this->auth)) {
                Response::redirect('photographs');
            }
        }

        $this->template->title = 'Register User';
        $this->template->content = View::forge('users/register.twig');
    }

}
