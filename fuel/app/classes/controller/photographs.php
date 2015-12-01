<?php

class Controller_Photographs extends Controller_Template {

    public $template = 'template.twig';
    private $auth = '';

    public function before() {
        parent::before();
        $this->auth = Auth::instance();
        $this->template->auth = $this->auth;
    }

    public function action_index() {

        // pagination object //
        $pagination = $this->paginate('paginate', Model_Photograph::count());

        $view = View::forge('photographs/index.twig');

        $view->set('photographs', Model_Photograph::query()
                        ->rows_offset($pagination->offset)
                        ->rows_limit($pagination->per_page)
                        ->get());

        $view->set('pagination', $pagination->render(), false);
        $this->template->title = "Photographs";
        $this->template->content = $view;
    }

    public function action_view($id = null) {
        is_null($id) and Response::redirect('photographs');

        $photograph = Model_Photograph::find($id, array('related' => 'comments'));

        if (!$photograph) {
            Session::set_flash('error', 'Could not find photograph');
            Response::redirect('photographs');
        }

        // Submit the comment //
        if (Input::post()) {
            if (Model_Comment::save_comment(Model_Comment::validate_comment(), $id, $this->auth)) {
                Response::redirect('photographs/view/' . $photograph->id);
            }
        }

        $data = array(
            'comments' => $photograph->comments,
            'photograph' => $photograph,
            'show_form' => $this->auth->check()
        );

        $this->template->title = $photograph->caption;
        $this->template->content = View::forge('photographs/view.twig', $data);
    }

    /**
     * Creates a pagination object.
     * @param string $name Name of the pagination object.
     * @param int $total Total count of items.
     * @return \Pagination
     */
    private function paginate($name, $total = 0) {
        $page_config = array(
            'pagination_url' => Uri::base(false) . 'photographs',
            'total_items' => $total,
            'per_page' => 3,
            'uri_segment' => 2,
            'show_first' => true,
            'show_last' => true,
        );
        return Pagination::forge($name, $page_config);
    }

}
