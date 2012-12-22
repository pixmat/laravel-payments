<?php

use Laravel\Log;
use Laravel\IoC;

class Payments_Payments_Controller extends Controller
{
	var $layout = "payments::layouts.default";

	public function action_index() {
		$paymentManager = IoC::resolve('paymentManager');

		//set the layout content and title
		$this->layout->paymentManager = $paymentManager;
		$view = View::make("payments::index");
		$view->paymentManager = $paymentManager;
		
		$this->layout->content = $view;
	}
}

