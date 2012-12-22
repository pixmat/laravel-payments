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
	}

	public function paymentLink(PaymentInvoice $invoice){
		$cclw = $this->config['cclw'];
		return "https://secure.paguelofacil.com/LinkDeamon.cfm?CCLW=$cclw&CMTN=$invoice->amount()&CDSC=$invoice->description()&invoice=$invoice->invoiceId()";
	}
}