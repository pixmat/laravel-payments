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

	public function action_chooseMethod($invoiceHash) {
		$invoice = Invoice::where_
		$view = View::make($this->configs->choosePaymentMethodView);
		$view->data['invoice'] = $invoice;
		$this->layout->content = $view;
	}

	public function action_processPayment($paymentGateway = '')
	{
		$this->layout->content = View::make($this->configs->paymentResultsView);
	}
	
	public function action_manual()
	{
		$this->layout->content = View::make('manual.index');
	}
}

