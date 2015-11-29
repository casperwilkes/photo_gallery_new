<?php

class Controller_Test extends Controller_Template {

    public function action_index() {
        $pass = \Auth::instance()->hash_password('pastword');

        Debug::dump($pass);

        // Create a new validator instance to play with
        $data['title'] = 'this title';
        $data['content'] = 'this is content';
        return new Fuel\Core\Response(Fuel\Core\View::forge('test/main', $data));
    }

}
