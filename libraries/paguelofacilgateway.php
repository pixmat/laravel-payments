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
	
	public function processResult(DataValue $response)
	{
		return array(
				IPaymentResult::RECORDED_KEY => $response->Oper,
				IPaymentResult::RECORDED_AMOUNT => $response->TotalPagado,
				IPaymentResult::RECORDED_DATE => $response->Fecha,
				IPaymentResult::RECORDED_TIME => $response->Hora,
				IPaymentResult::RECORDED_STATUS => $response->Estado,
				IPaymentResult::RECORDED_TYPE => $response->Tipo,
				IPaymentResult::RECORDED_CLIENT_NAME => $response->Usuario,
				IPaymentResult::RECORDED_CLIENT_EMAIL => $response->Email,
				IPaymentResult::RECORDED_GATEWAY_NAME => $response->$this->name(),

				IPaymentResult::SUCCESSFUL => ($response->Estado === 'Aprobada'),
				IPaymentResult::FAILED => ($response->Estado === 'Denegado'),
				);
		
	}
}