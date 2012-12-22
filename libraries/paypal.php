<?php

use Laravel\Log;

class PaypalGateway implements PaymentService
{
	var $config = null;
	public function __construct(array $config = null)
	{
		if(is_null($config)){
			throw new Exception('Please provide a valid paypal configuration');
		}
		$this->config = $config;
	}
	
	public function paymentLink(Invoice $invoice)
	{
		return $this->config['paymentUrl'] . "...";
	}
}