<?php

class Controller_Admin extends Controller_Template {

    public function before() {
        parent::before();
        if (!Auth::check() or Auth::get('group', 2) != 1) {
            Response::redirect('photographs/');
        }
    }

    public function action_index() {

        $nav = array(
            array('path' => 'admin/photos', 'value' => 'List Photos'),
            array('path' => 'admin/users', 'value' => 'Manage Users'),
            array('path' => 'users/logout', 'value' => 'Logout')
        );
        $data['navigation'] = $nav;
        $this->template->title = 'Admin &raquo; Index';
        $this->template->content = View::forge('admin/index', $data);
    }

    public function action_users() {
        $data = array(
            'users' => Model_User::find('all'),
            'group' => Auth::group('Simplegroup')
        );

        $this->template->title = 'Manage Users';
        $this->template->content = View::forge('admin/users', $data);
    }

    public function action_users_edit($id = null) {
        is_null($id) and Response::redirect('admin/users');

        $view = View::forge('admin/users_edit');
        $user = Model_User::find($id);
        $view->set('user', $user);
        $fieldset = Fieldset::forge('edit_user');

        Model_User::populate_edit($fieldset, $user);

        if (Input::post()) {
            if (Model_User::save_edit($fieldset, $user)) {
                Response::redirect('admin/users');
            } else {
                $fieldset->repopulate();
            }
        }
        $view->set('image', unserialize($user->profile_fields)['image']);
        $view->set('edit_form', $fieldset->build(), FALSE);
        $this->template->title = 'Edit User';
        $this->template->content = $view;
    }

    public function action_users_delete($id = null) {
        is_null($id) and Response::redirect('admin/users_edit');

        Model_User::remove_user($id);

        Response::redirect('admin/users_edit');
    }

    public function action_photos() {
        $photos = Model_Photograph::find('all');
        foreach ($photos as $photo) {
            $photo->set('comment_count', count(Model_Comment::query()->where('photograph_id', $photo->id)->get()));
        }
        $data['photos'] = $photos;
        $this->template->title = 'Admin Photographs';
        $this->template->content = View::forge('admin/photos', $data);
    }

    public function action_photos_delete($id = null) {
        is_null($id) and Response::redirect('admin/list_photos');

        Model_Photograph::remove_photo($id);

        Response::redirect('admin/photos');
    }

    public function action_photos_edit($id = null) {
        is_null($id) and Response::redirect('photographs');

        if (!$photograph = Model_Photograph::find($id)) {
            Session::set_flash('error', 'Could not find photograph');
            Response::redirect('photographs');
        }

        $form = Fieldset::forge('edit_image');
        Model_Photograph::populate_edit($form, $photograph);

        if (Input::post()) {
            if (Model_Photograph::save_edit($form, $photograph)) {
                Response::redirect('admin/photos');
            } else {
                $form->repopulate();
            }
        }
        $this->template->set_global('photograph', $photograph, false);
        $this->template->set_global('form', $form, false);
        $this->template->title = "Photographs";
        $this->template->content = View::forge('admin/photos_edit');
    }

    public function action_photos_new() {
        $form = Fieldset::forge('new_image');
        Model_Photograph::populate_upload($form);

        if (Input::method() == 'POST') {
            if (Model_Photograph::save_upload($form)) {
                Response::redirect('admin/photos');
            } else {
                $form->repopulate();
            }
        }
        $data['form'] = $form;
        $this->template->title = "Upload Photograph";
        $this->template->content = View::forge('admin/photos_new', $data, false);
    }

    public function action_comments($id = null) {
        is_null($id) and Fuel\Core\Response::redirect('admin/photos');
        /**
         * @todo make relational
         */
        $data['photo'] = Model_Photograph::find($id);
        $data['comments'] = Model_Comment::query()->where('photograph_id', $id)->get();

        $this->template->title = 'Photo Gallery: Admin';
        $this->template->content = View::forge('admin/comments', $data);
    }

    public function action_comments_delete($image_id = null, $comment_id = null) {
        is_null($image_id) or is_null($comment_id) and Response::redirect('admin/photos');

        Model_Comment::remove_comment($comment_id);

        response::redirect('admin/comments/' . $image_id);
    }

}
