<?php

use Laravel\Log;

class Payments_Payments_Controller extends Controller
{

	public function action_index() {
		$paymentManager = Laravel\IoC::resolve('paymentManager');
		Log::debug('Payment manager: ' . print_r($paymentManager, true));

		//set the layout content and title
		//$this->layout->modelName = $modelName;
		$this->layout->content = View::make("payments::paguelofacil", array(
				'paymentManager' => Laravel\IoC::resolve('paymentManager'),
		));
	}
}