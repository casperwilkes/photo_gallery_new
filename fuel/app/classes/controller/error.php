<?php

class Controller_Error extends Controller {

    /**
     * The 404 action for the application.
     * @access  public
     * @return  Response
     */
    public function action_404() {
        return Response::forge(Presenter::forge('error/404', 'view', null, 'error/404.twig'), 404);
    }

}
