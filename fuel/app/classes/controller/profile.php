<?php

class Controller_Profile extends Controller_Template {

    public $template = 'template.twig';
    private $auth = '';

    public function before() {
        parent::before();

        $this->auth = Auth::instance();
        $this->template->auth = $this->auth;
    }

    public function action_index() {
        if (!$this->auth->check()) {
            Session::set_flash('error', 'No user currently logged in');
            Response::redirect('/');
        }

        $bio = strlen($this->auth->get('bio')) ? $this->auth->get('bio') : 'User has not set up a bio yet';

        $data = array(
            'auth' => $this->auth,
            'username' => $this->auth->get_screen_name(),
            'bio' => $bio,
            'image' => $this->auth->get('image')
        );

        $this->template->title = 'Profile';
        $this->template->content = View::forge('profile/index.twig', $data);
    }

    public function action_view($user_name = null) {

        if (is_null($user_name)) {
            Session::set_flash('error', 'No user selected');
            Response::redirect('/');
        }

        // Get the user based on username //
        $user = Model_User::find('first', array('where' => array(array('username', $user_name))));

        if (!$user) {
            Session::set_flash('error', 'User not found');
            Response::redirect('/');
        }

        $profile_fields = unserialize($user->profile_fields);

        $data = array(
            'username' => $user->username,
            'bio' => (strlen($profile_fields['bio'])) ? $profile_fields['bio'] : $user->username . ' has not shared a biography yet',
            'image' => $profile_fields['image']
        );

        $this->template->title = 'Profile';
        $this->template->content = View::forge('profile/index.twig', $data);
    }

    public function get_edit($edit = null) {

        !$this->auth->check() and Response::redirect('photographs/');

        $show_form = false;

        if (is_null($edit) or !in_array($edit, $this->update_options(), true)) {
            $view = View::forge('profile/edit_menu.twig');
            $view->set('menu', $this->update_options());
        } else {
            $view = View::forge('profile/edit.twig');
            $view->set('segment', ucfirst($edit));
            $show_form = true;
        }

        $view->set('username', $this->auth->get_screen_name());

        if ($show_form) {
            $form_value = is_null(Input::post($edit, null)) ? $this->auth->get($edit) : Input::post($edit);
            $view->set_global('value', $form_value);
            $view->set('form', 'profile/_form' . ucfirst($edit).'.twig');
        }

        $this->template->title = 'Profile &raquo; Update';
        $this->template->content = $view;
    }

    public function post_edit($edit = null) {

        if (!$this->auth->check() or is_null($edit)) {
            Session::set_flash('Could not locate proper update process');
            Response::redirect('photographs/');
        }

        if (Security::check_token()) {
            $val = Model_Profile::validate_form($edit);
            if (Model_Profile::save_profile($val, $this->auth, $edit)) {
                Response::redirect('profile/');
            }
        } else {
            Log::error('Invalid CSRF Token', __METHOD__);
            Session::set_flash('error', 'There was a problem updating your ' . $edit . ' please try again');
        }

        $this->get_edit($edit);
    }

    /**
     * Array to show profile options and compare options against.
     * @return array
     */
    private function update_options() {
        return array('bio', 'image', 'email', 'password');
    }

}
