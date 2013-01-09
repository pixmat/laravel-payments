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

	public function action_chooseMethod($invoiceHash) 
	{
		$invoice = Invoice::where_hash($invoiceHash)->first();
		$errors = new Messages();
		if(!$invoice){
			$errors->add('epicentro', 'Invoice not found');
		}
		$view = View::make($this->configs->choosePaymentMethodView, array(
				'invoice' => $invoice->asIInvoice(),
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

		$invoice = Invoice::where_hash($query->invoice)->first();
		if(!$invoice){
			$errors->add('epicentro', "Invoice ($query->invoice) not found");
		} else if ($invoice->total_payments >= $invoice->total_amount) {
			$errors->add('epicentro', "Invoice ($invoice->hash) is not pending for payment");
		} else {
			$payment = Payment::fromPaymentResult($result);
			$payment->invoice = $invoice;
			$invoice->total_payments = $payment->recorded_amount;
			$payment->save();
			$invoice->save();
		}
			
		$this->layout->content = View::make($this->configs->paymentResultsView, array($errors));
	}

	public function action_manual()
	{
		$this->layout->content = View::make('manual.index');
	}
}

