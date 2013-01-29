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
		//parse query string
		$queryString = $this->parseQueryString();
		//find invoice by hash
		$invoice = $this->findInvoice($queryString->invoice);
		$viewName = $this->resolveViewName();
		$viewData = array('invoice' => $invoice);
		
		try {
			$this->checkInvoiceStatus($invoice);
			//get payment gateway by name
			$gateway = $this->getPaymentGateway($paymentGateway);
			//process payment service response
			$result = $this->getPaymentServiceResponse($gateway, $queryString);
			//construct payment from payment service result
			$payment = $this->constructPaymentFromPaymentServiceResult($result);

			//register payment to bill
			$invoice->paybill($payment);
				
			$viewData['gateway'] = $gateway;
			$viewData['status'] = 'success';
			$viewData['message'] = 'Pago realizado correctamente';
		}catch(PaymentException $ex){
			$viewData['status'] = 'error';
			$viewData['message'] = $ex->getMessage();
		}catch(Exception $ex){
			$viewData['status'] = 'error';
			$viewData['message'] = 'Error no esperado procesando el pago';
		}

		$this->layout->title = 'Proceso de pago';
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
			throw new PaymentException("Opcion de pago incorrecta ($paymentGateway)");
		}
		return $gateway;
	}

	private function getPaymentServiceResponse($paymentGateway, $queryString)
	{
		$result = $paymentGateway->processResult($queryString);
		$status = $result[IPaymentResult::RECORDED_STATUS];
		if ( $result[IPaymentResult::FAILED] ){
			Log::warn("Payment rejected by payment service, response is: " . print_r($result, true));
			throw new PaymentException("Pago rechazado por el servicio de pago");
		}
		return $result;
	}

	private function findInvoice($hash)
	{
		$invoiceDao = IoC::resolve('invoicedao');
		$bill = $invoiceDao->findByHashKey($hash);
		return $bill;
	}

	private function checkInvoiceStatus(IInvoice $bill)
	{
		if($bill->isPaid()){
			$hash = $bill->hashKey();
			Log::warn("Invoice [$hash] is not pending for payment");
			throw new PaymentException("Esta factura ya ha sido cancelada");
		}
		return true;
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

