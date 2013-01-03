<?php

use Laravel\Log;
use Laravel\IoC;
use Laravel\Config;
use Laravel\View;

class Payments_Payments_Controller extends Controller
{
	var $configs = null;

	public function __construct()
	{
		$this->configs = IoC::resolve('configs');
		$this->layout = View::make($this->configs->layout);
	}

	public function action_chooseMethod() {
		$this->layout->content = View::make($this->configs->choosePaymentMethodView);
	}

	public function action_processPayment($paymentGateway = '')
	{
		print_r($paymentGateway);
		$this->layout->content = View::make($this->configs->paymentResultsView);
	}
}

