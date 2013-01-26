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

	public function __construct()
	{
		$this->configs = IoC::resolve('configs');
		$this->layout = View::make($this->configs->layout);
	}

	public function action_chooseMethod($hash, $processName=null)
	{
		Log::debug("hash: $hash, process name: $processName");
		$processName = is_null($processName) ? 'payment' : $processName;
		$invoiceDao = IoC::resolve('invoicedao');
		$errors = new Messages();
		$invoice = false;
		try {
			$invoice = $invoiceDao->findByHashKey($hash);
		}catch(Exception $ex){
			$errors->add('epicentro', $ex->getMessage());
		}
		$view = View::make($this->configs->choosePaymentMethodView, array(
				'invoice' => $invoice,
				'processName' => $processName,
				'errors' => $errors
		));
		$this->layout->content = $view;
	}

	public function action_processPayment($paymentGateway = '')
	{
		$invoiceDao = IoC::resolve('invoicedao');
		$paymentDao = IoC::resolve('paymentdao');
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
			$invoice = $invoiceDao->findByHashKey($query->invoice);
			if($invoice->isPaid()){
				throw new Exception("Invoice [$query->invoice] is not pending for payment");
			}
			$payment = $paymentDao->fromPaymentGatewayResult($result);
			$invoice->paybill($payment);
		}catch(Exception $ex){
			$errors->add('epicentro', $ex->getMessage());
		}
		Log::debug('Rendering payment results view');
		
		$isUserRegistration =  Session::get('userRegistration', 'no') === 'yes';
		if ($isUserRegistration){
			$view = View::make($this->configs->wellcomePage, array('errors'=>$errors));
		}else {
			$view = View::make($this->configs->paymentResultsView, array(
					'invoice' => $bill,
					'gateway' => $gateway,
					'errors' => $errors,
			));
		}
		
	}

	public function action_manual()
	{
		$this->layout->content = View::make('manual.index');
	}
}

