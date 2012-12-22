<?php

use Laravel\Log;

class PagueloFaciltGateway implements PaymentService
{
	var $config = array();
	public function __construct(array $config = null)
	{
		if(is_null($config)){
			throw new Exception('Please provide some configurations for "Paguelo Facil" service');
		}
		$this->config = $config;
		
		include('/path/to/payments/payments.php');
		
		$p = new PHP_Payments;
		
		$config = Payment_Utility::load('config', '/path/to/your/gateway/config');
		$params = array('cc_number' => 4111111111111111, 'amt' => 35.00, 'cc_exp' => '022016', 'cc_code' => '203');
		
		$response = $p->oneoff_payment('name_of_payment_driver', $params, $config);
	}

	public function paymentLink(PaymentInvoice $invoice)
	{
		$cclw = $this->config['cclw'];
		$requestUrl = $this->config['paymentUrl'];
		return "$requestUrl?CCLW=$cclw&CMTN=$invoice->amount()&CDSC=$invoice->description()&invoice=$invoice->invoiceId()";
	}
}