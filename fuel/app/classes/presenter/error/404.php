<?php

/**
 * The welcome 404 presenter.
 *
 * @package  app
 * @extends  Presenter
 */
class Presenter_Error_404 extends Presenter
{
	/**
	 * Prepare the view data, keeping this in here helps clean up
	 * the controller.
	 *
	 * @return void
	 */
	public function view()
	{
		$messages = array('Comming Soon!... Probably Not', 'Oh No', 'Uh Oh!', 'Nope, not here.', 'Ooops');
		$this->title = $messages[array_rand($messages)];
	}
}
