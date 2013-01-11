<?php

use Laravel\Log;
use Laravel\IoC;
use Laravel\Config;
use Laravel\View;
use Laravel\Redirect;
use Laravel\Messages;

class Payments_Payments_Controller extends Controller
{
	var $configs = null;
	var $invoiceDao = null;
	var $paymentDao = null;

	public function __construct()
	{
		$this->configs = IoC::resolve('configs');
		$this->layout = View::make($this->configs->layout);
		$this->invoiceDao = IoC::resolve('invoicedao');
		$this->paymentDao = IoC::resolve('paymentdao');
	}

	public function action_chooseMethod($invoiceHash)
	{
		$errors = new Messages();
		$invoice = false;
		try {
			$invoice = $this->invoiceDao->findByHashKey($invoiceHash);
		}catch(Exception $ex){
			$errors->add('epicentro', $ex->getMessage());
		}
		$view = View::make($this->configs->choosePaymentMethodView, array(
				'invoice' => $invoice,
				'errors' => $errors
		));
		$this->layout->content = $view;
	}

	public function action_processPayment($paymentGateway = '')
	{
		parse_str($_SERVER['QUERY_STRING'], $queryString);
		$query = new DataValue($queryString);
		$errors = new Messages();
		$gateway = IoC::resolve($paymentGateway);
		if ( !isset($gateway) || is_null($gateway) ){
			$errors->add('epicentro', "Invalid payment service option ($paymentGateway)");
		}

		$result = $gateway->processResult($query);
		$status = $result[IPaymentResult::RECORDED_STATUS];
		if ( $result[IPaymentResult::FAILED] ){
			$errors->add('epicentro', "Payment not approved, the payment service says: [$status]");
		}

		$invoice = false;
		$payment = false;
		try {
			$invoice = $this->invoiceDao->findByHashKey($query->invoice);
			if($invoice->isPaid()){
				throw new Exception("Invoice [$query->invoice] is not pending for payment");
			}
			$payment = $this->paymentDao->fromPaymentGatewayResult($result);
			$invoice->paybill($payment);
		}catch(Exception $ex){
			$errors->add('epicentro', $ex->getMessage());
		}
		$this->layout->content = View::make($this->configs->paymentResultsView, array($errors));
	}

	public function action_manual()
	{
		$this->layout->content = View::make('manual.index');
	}
}

