<?php

Autoloader::namespaces(array(
	'Payments' => Bundle::path('epicentro-payment') . 'models',
));

Laravel\IoC::register('paypalGateway', function()
{
	$paypalGateway = new PaypalPaymentGateway();
	return $paypalGateway;
});

Laravel\IoC::register('pagueloFacilGateway', function()
{
	$pagueloFacilGateway = new PagueloFaciltGateway();
	return $pagueloFacilGateway;
});

/**
 * Register IoC objects
 */
Laravel\IoC::register('paymentManager', function()
{
	$enabledPaymentGateways = Config::get('laravel-payments::laravel-payments.paymentGatewayList');
	$paymentGateways = array();
	foreach ($enabledPaymentGateways as $gatewayName){
		$paymentGateways[] = Laravel\IoC::resolve($gatewayName);
	}
	return new PaymentManager($paymentGateways);
});