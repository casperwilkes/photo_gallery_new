<?php

class Controller_Admin extends Controller_Template {

    /**
     * Set this to run before any methods
     */
    public function before() {
        parent::before();
        if (!Auth::check() and basename(Uri::current()) != 'login') {
            Response::redirect('login/');
        }
    }

    /**
     * Logs admin in. 
     */
    public function action_login() {
        $auth = Auth\Auth::instance();
        if ($auth->check()) {
            Response::redirect('admin/');
        }
        $view = View::forge('admin/login');
        $form = Model_User::populate_login(Fieldset::forge('login'));
        $form->repopulate();
        if (Input::post()) {
            $result = Model_User::validate_login($form, $auth, Input::post());
            if ($result['errors']) {
                $view->set_safe('message', $result['message']);
            } else {
                Session::set_flash('success', $result['username'] . ' Successfully Logged in');
                Response::redirect('admin/');
            }
        }
        $view->set_safe('form', $form->build());

        $this->template->title = 'Admin Login';
        $this->template->content = $view;
    }

    /**
     * Main page.
     * Provides navigation around admin section.
     */
    public function action_index() {
        $data["subnav"] = $this->nav_items();
        $this->template->title = 'Admin Main';
        $this->template->content = View::forge('admin/index', $data);
    }

    /**
     * Lists all photograph information in table.
     */
    public function action_list_photos() {
        $photos = Model_Photograph::find('all');
        foreach ($photos as $photo) {
            $photo->set('comment_count', count(Model_Comment::query()->where('photograph_id', $photo->id)->get()));
        }
        $data['photos'] = $photos;
        $this->template->title = 'Admin Photographs';
        $this->template->content = View::forge('admin/list_photos', $data);
    }

    /**
     * Edit comments on a given photograph.
     * @param int $id Id of the photgraph
     */
    public function action_comments($id = null) {
        is_null($id) and Fuel\Core\Response::redirect('admin/list_photos');

        $data['photo'] = Model_Photograph::find($id);
        $data['comments'] = Model_Comment::query()->where('photograph_id', $id)->get();

        $this->template->title = 'Photo Gallery: Admin';
        $this->template->content = View::forge('admin/comments', $data);
    }

    /**
     * Deletes photos by id.
     * @param int $id Photo id
     */
    public function action_delete_photo($id = null) {
        is_null($id) and Response::redirect('admin/list_photos');
        $photograph = Model_Photograph::find($id);
        if ($photograph) {
            if ($photograph->delete()) {
                if (!Model_Photograph::remove_photo($photograph->filename)) {
                    Log::error('Photo not deleted', __METHOD__);
                }

                Session::set_flash('success', 'Deleted photograph');
            } else {
                Session::set_flash('error', 'Could not delete photograph at this time');
            }
        } else {
            Session::set_flash('error', 'Could not delete photograph');
        }
        Response::redirect('admin/list_photos');
    }

    /**
     * Deletes comment by id.
     * @param int $id Comment id
     */
    public function action_delete_comment($id = null) {
        /**
         * If successfully deleted, need to delete file as well. 
         */
        is_null($id) and Response::redirect('admin/list_photos');
        $comment = Model_Comment::find($id);
        if ($comment) {
            $comment->delete();
            Session::set_flash('success', 'Comment deleted');
            response::redirect('admin/comments');
        } else {
            Session::set_flash('error', 'Could not delete comment');
            Response::redirect('admin/list_photos');
        }
    }

    /**
     * Uploads photo.
     * Saves information to database and saves photo to assets/img dir.
     */
    public function action_upload_photo() {
        if (Input::method() == 'POST') {
            $val = Model_Photograph::validate('create');
            if ($val->run()) {
                $config = array(
                    'auto_process' => FALSE,
                    'path' => DOCROOT . 'assets/img',
                    'ext_whitelist' => array('img', 'jpg', 'jpeg', 'gif', 'png'),
                );

                Upload::process($config);
                if (Upload::is_valid()) {
                    Upload::save();
                    $upload = Upload::get_files(0);
                    $photograph = Model_Photograph::forge(array(
                                'filename' => $upload['name'],
                                'type' => $upload['type'],
                                'size' => $upload['size'],
                                'caption' => Input::post('caption'),
                    ));
                    if ($photograph and $photograph->save()) {
                        Session::set_flash('success', 'Added photograph: ' . $photograph->caption);
                        Response::redirect('admin/list_photos');
                    } else {
                        Session::set_flash('error', 'Could not save photograph.');
                    }
                } else {
                    $errors = Upload::get_errors();
                    Session::set_flash('error', 'Could not upload image');
                }
            } else {
                Session::set_flash('error', $val->error());
            }
        }
        $this->template->title = "Upload Photograph";
        $this->template->content = View::forge('admin/upload_photo');
    }

    /**
     * Edits photo based on id. 
     * @param int $id Photo id.
     */
    public function action_edit_photo($id = null) {
        is_null($id) and Response::redirect('photographs');

        if (!$photograph = Model_Photograph::find($id)) {
            Session::set_flash('error', 'Could not find photograph');
            Response::redirect('photographs');
        }

        $form = \Fuel\Core\Form::forge('edit_image');
        $form->add('caption', 'Caption:', array('value' => $photograph->caption));
        $form->add('submit', ' ', array('type' => 'submit', 'value' => 'Edit'));
        if (Input::post()) {
            $val = Model_Photograph::validate('edit');

            if ($val->run()) {
                $photograph->caption = Input::post('caption');

                if ($photograph->save()) {
                    Session::set_flash('success', 'Updated photograph: ' . $photograph->caption);
                    Response::redirect('admin/list_photos');
                } else {
                    Session::set_flash('error', 'Could not update photograph: ' . $photograph->caption);
                }
            } else {
                if (Input::method() == 'POST') {
                    $photograph->caption = $val->validated('caption');
                    Session::set_flash('error', $val->error());
                }
            }
        }
        $this->template->set_global('photograph', $photograph, false);
        $this->template->set_global('form', $form, false);
        $this->template->title = "Photographs";
        $this->template->content = View::forge('admin/edit_photo');
    }

    /**
     * Edits admin information.
     * Acts as landing and receiving pages.
     * @param int $id Id of user.
     * @param \Fieldset $fieldset Form object
     * @param array $errors Any errors the occured. 
     */
    public function get_edit_user($id = NULL, $fieldset = NULL, $errors = NULL) {
        is_null($id) and Response::redirect('admin');

        $view = View::forge('admin/edit_user');
        $user = Model_User::find($id);
        $view->set('user', $user);
        if (empty($fieldset)) {
            $fieldset = Fieldset::forge('edit_user');
            Model_User::populate_edit($fieldset, $user);
        }
        if ($errors) {
            $view->set_safe('errors', $errors);
        }
        $view->set('edit_form', $fieldset->build(), FALSE);
        $this->template->title = 'Edit User';
        $this->template->content = $view;
    }

    /**
     * Handles form submission form editing.
     * @param int $id Id of user
     */
    public function post_edit_user($id = null) {
        is_null($id) and Response::redirect('admin/');

        $user = Model_User::find($id);
        $fieldset = Model_user::populate_edit(Fieldset::forge('edit_user'), $user);
        $fieldset->repopulate();
        $result = Model_User::validate_edit($fieldset, Input::post(), $user);
        if (!is_null($result['return'])) {
            if ($result['return']) {
                Session::set_flash('success', 'User has been updated');
            } else {
                Session::set_flash('error', 'User could not be updated');
            }
        }
        // Send results back to the get_edit function //
        $this->get_edit_user($user->id, $fieldset, $result['errors']);
    }

    /**
     * Log admin out. 
     */
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

    /**
     * Navigational links 
     * @return array
     */
    private function nav_items() {
        return array(
            array('path' => 'admin/list_photos', 'value' => 'List Photos'),
            array('path' => 'admin/edit_user/1', 'value' => 'Manage User'),
            array('path' => 'admin/logout', 'value' => 'Logout')
        );
    }

}
