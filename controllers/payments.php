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
	}
	
	public function action_index() {
		return View::make("payments::index");
	}
	
	public function action_wellcome()
	{
		return View::make($this->configs->wellcomeView);
	}
	
	public function action_payment()
	{
		return View::make($this->configs->paymentView);
	}
}

