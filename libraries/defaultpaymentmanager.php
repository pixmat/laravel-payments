<?php

use Laravel\Log;

class DefaultPaymentManager implements PaymentManager
{

	var $gateways = array();

	public function __construct(array $enabledPaymentGateways = NULL)
	{
		if(!$this->isValid($enabledPaymentGateways)) {
			throw new Exception('You need to provide at least one payment service gateway');
		}

		foreach ($enabledPaymentGateways as $gatewayName){
			$gateway = IoC::resolve($gatewayName);
			Log::debug("Resolving $gatewayName in IoC container resulted in: $gateway");
			array_push($this->gateways, $gateway);
		}

		Log::info('List of payment gateways stored');
	}

	private function isValid(array $enabledPaymentsGateways = null)
	{
		Log::info('validating enabled payment gateways');
		if(is_null($enabledPaymentsGateways)){
			Log::debug('Invalid list of enabled payment gateways: Null value');
			return false;
		}

		if(!is_array($enabledPaymentsGateways)){
			Log::debug('Invalid list of enabled payment gateways: is not an array');
			return false;
		}

		if(count($enabledPaymentsGateways) <= 0){
			Log::debug('Invalid list of enabled payment gateways: has zero (0) elements');
			return false;
		}

		Log::debug('Valid list of enabled payment gateways');
		return true;
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
