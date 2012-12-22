<?php

use Laravel\Log;

class Payments_Payments_Controller extends Controller
{

	public function action_index($paymentService) {
		Log::debug("Using $paymentService for test index");
		$view = View::make("payments::paguelofacil", array(
				//"paymentService" => $paymentService,
		));
	
		//set the layout content and title
		//$this->layout->modelName = $modelName;
		$this->layout->content = $view;
	}
}