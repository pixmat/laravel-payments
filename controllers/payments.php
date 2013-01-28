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
		$this->layout->title = 'Escoja su metodo de pago';
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
		$invoice = false;
		$viewName = $this->resolveViewName();
		try {
			//parse query string
			$queryString = $this->parseQueryString();
			//get payment gateway by name
			$gateway = $this->getPaymentGateway($paymentGateway);
			//process payment service response
			$result = $this->getPaymentServiceResponse($gateway, $queryString);
			//construct payment from payment service result
			$payment = $this->constructPaymentFromPaymentServiceResult($result);
			//find invoice by hash
			$invoice = $this->findInvoice($queryString->invoice);
				
			//register payment to bill
			$invoice->paybill($payment);
			
			$viewData = array(
					'invoice' => $invoice,
					'gateway' => $gateway,
			);
		}catch(Exception $ex){
			$errors = new Messages();
			$errors->add('epicentro', $ex->getMessage());
			$viewData = array('errors'=>$errors);
		}
		
		$this->layout->title = 'Pago procesado';
		Log::debug('Rendering payment results view');
		$view = View::make($viewName, $viewData);
		$this->layout->content = $view;

	}

	private function parseQueryString()
	{
		parse_str($_SERVER['QUERY_STRING'], $queryString);
		$query = new DataValue($queryString);
		return $query;
	}

	private function getPaymentGateway($paymentGateway = '')
	{
		$gateway = IoC::resolve($paymentGateway);
		if ( !isset($gateway) || is_null($gateway) ) {
			Log::warn("Invoice [$query->invoice] is not pending for payment");
			throw new Exception("Opcion de pago incorrecta ($paymentGateway)");
		}
		return $gateway;
	}

	private function getPaymentServiceResponse($paymentGateway, $queryString)
	{
		$result = $paymentGateway->processResult($queryString);
		$status = $result[IPaymentResult::RECORDED_STATUS];
		if ( $result[IPaymentResult::FAILED] ){
			Log::warn("Payment rejected by payment service, response is: " . print_r($result, true));
			throw new Exception("Pago rechazado por el servicio de pago");
		}
		return $result;
	}

	private function findInvoice($hash)
	{
		$invoiceDao = IoC::resolve('invoicedao');
		$bill = $invoiceDao->findByHashKey($hash);
		if($bill->isPaid()){
			Log::warn("Invoice [$hash] is not pending for payment");
			throw new Exception("La factura indicada ya ha sido cancelada");
		}
		return $bill;
	}

	private function constructPaymentFromPaymentServiceResult(array $result){

		$paymentDao = IoC::resolve('paymentdao');
		$payment = $paymentDao->fromPaymentGatewayResult($result);
		return $payment;
	}

	private function resolveViewName()
	{
		$isUserRegistration =  Session::get('userRegistration', 'no') === 'yes';
		return $isUserRegistration? $this->configs->welcomePage : $this->configs->paymentResultsView;
	}

	public function action_manual()
	{
		$this->layout->content = View::make('manual.index');
	}
}

