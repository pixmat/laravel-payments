<?php
use Laravel\Log;
use Laravel\HTML;

class PagueloFacilGateway implements PaymentService
{
	var $config = null;
	
	public function __construct(array $config = null)
	{
		if(is_null($config)){
			throw new Exception('Please provide some valid configurations for Paguelo Facil');
		}
		$this->config = new Configuration($config);
	}
	
	public function __toString()
	{
		return $this->name();
	}
	
	public function name()
	{
		if(isset($this->config->name)){
			return $this->config->name;
		}
		return 'paguelofacil';
	}

	public function paymentLink(IInvoice $invoice)
	{
		$paymentUrl = $this->config->paymentUrl;
		$cclw = HTML::entities($this->config->cclw);
		$cdsc = HTML::entities($invoice->description());
		$cmtn = number_format($invoice->amount(), 2, '.', '');
		$invoiceId = $invoice->hashKey();
		return "$paymentUrl?CCLW=$cclw&CMTN=$cmtn&CDSC=$cdsc&invoice=$invoiceId";
	}
	
	public function buttonImage()
	{
		return 'https://secure.paguelofacil.com/images/botones/acceptamos_paguelofacil_1b_200.png';
	}
}