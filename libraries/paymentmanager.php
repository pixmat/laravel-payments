<?php

interface PaymentManager 
{
	function getPaymentGateways();
}

class DefaultPaymentManager implements PaymentManager
{

	var $gateways = array();
	
	public function __construct(array $paymentGateways = NULL)
	{
		if(is_null($paymentGateways) || count($paymentGateways) <= 0) {
			throw new Exception('You need to provide at least one payment service gateway');
		}
		$this->gateways = $paymentGateways;
	}
	
	public function getPaymentGateways(){
		return $this->gateways;
	}
}
