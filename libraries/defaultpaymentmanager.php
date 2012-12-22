<?php
use Laravel\Log;

class DefPaymentManager implements PaymentManager
{

	var $gateways = array();

	public function __construct(array $paymentGateways = NULL)
	{
		Log::debug('DefaultPaymentManger construct called with ', print_r($paymentGateways, true));
		if(is_null($paymentGateways) || count($paymentGateways) <= 0) {
			Log::warn('Invalid value for enabled payment gateways');
			throw new Exception('You need to provide at least one payment service gateway');
		}
		Log::debug('Listo of payment gateways stored');
		$this->gateways = $paymentGateways;
	}

	public function getPaymentGateways(){
		return $this->gateways;
	}
	
	public function __toString()
	{
		return "DefaultPaymentManager, payment services enabled: $this->count()";
	}
	
	private function count()
	{
		return count($this->gateways);
	}
}
