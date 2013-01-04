<?php
class ManualPayment implements PaymentService
{
	var $config = null;

	public function __construct($config = null)
	{
		if(is_null($config)){
			throw new Exception('Please provide a valid configuration for manual payment');
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
		return 'manual';
	}

	public function paymentLink(Invoice $invoice)
	{
		return Bundle::path('payments') . 'manual';
	}

	public function buttonImage()
	{
		return 'https://secure.paguelofacil.com/images/botones/acceptamos_paguelofacil_1b_200.png';
	}
}