<?php

use Laravel\Log;

include Bundle::path('laravel-payments') . 'vendor/php-payments/lib/payments.php';

class PaypalPaymentGateway implements PaymentService
{
	var $payments;
	
	function __construct(Configuration $config) {
		$this->payments = new PHP_Payments();
		//TODO: find out the right config file format
		$this->config = Payment_Utility::load('config', Bundle::path('laravel-payments') . 'config/paypal.php');
	}
	
	public function doPayment(PaymentInvoice $paymentInvoice){
		//TODO implement correctly
		$response = $this->payments->payment_action('gateway_name', $params, $config); 
		//TODO process response
	}
}