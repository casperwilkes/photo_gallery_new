<?php

class Controller_Photographs extends Controller_Template {

    /**
     * Landing page.
     * View all photographs based on pagination.
     */
    public function action_index() {
        // pagination object //
        $pagination = $this->paginate('paginate', Model_Photograph::count());
        $data['photographs'] = Model_Photograph::query()
                ->rows_offset($pagination->offset)
                ->rows_limit($pagination->per_page)
                ->get();
        $data['pagination'] = $pagination;
        $this->template->title = "Photographs";
        $this->template->content = View::forge('photographs/index', $data);
    }

    /**
     * View a single photograph.
     * @param int $id Photo id.
     */
    public function action_view($id = null) {
        is_null($id) and Response::redirect('photographs');

        if (!$photograph = Model_Photograph::find($id)) {
            Session::set_flash('error', 'Could not find photograph');
            Response::redirect('photographs');
        }
        $auth = Auth::instance();

        $view = View::forge('photographs/view');
        $form = Model_Comment::populate_build(Fieldset::forge('comment'));
        $form->repopulate();

        if (Input::method() == 'POST') {
            $result = Model_Comment::validate_comment($form, $id, $auth);
            if ($result['error']) {
                if (isset($result['message'])) {
                    $view->set_safe('errors', $result['message']);
                } else {
                    Session::set_flash('error', 'Comment could not be saved at this time');
                }
            } else {
                $form->populate(array('comment' => ''));
                Session::set_flash('success', 'Comment has been saved');
                Response::redirect(Uri::current());
            }
        }

        $view->set('comments', Model_Comment::query()->where('photograph_id', $id)->get());
        $view->set('photograph', $photograph);
        $view->set_safe('form_comments', $form);

        $this->template->title = "Photograph";
        $this->template->content = $view;
    }

    /**
     * Creates a pagination object.
     * @param string $name Name of the pagination object.
     * @param int $total Total count of items.
     * @return \Pagination
     */
    private function paginate($name, $total = 0) {
        $page_config = array(
            'pagination_url' => Uri::base(false) . 'photographs/index/',
            'total_items' => $total,
            'per_page' => 3,
            'uri_segment' => 3,
            'show_first' => true,
            'show_last' => true,
        );
        return Pagination::forge($name, $page_config);
    }

}

