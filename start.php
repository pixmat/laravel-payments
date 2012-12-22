<?php

Autoloader::namespaces(array(
	'Payments' => Bundle::path('laravel-payment') . 'libraries',
	'Payments\Models' => Bundle::path('laravel-payment') . 'models',
));

Laravel\IoC::register('payments.paypal', function()
{
	$paypalGateway = new PaypalPaymentGateway();
	return $paypalGateway;
});

Laravel\IoC::register('payments.pagueloFacil', function()
{
	$config = Config::get('payments::payments.pagueloFacilConfig');
	return new PagueloFaciltGateway($config);
});

/**
 * Register IoC objects
*/
Laravel\IoC::singleton('paymentManager', function()
{
	$enabledPaymentGateways = Config::get('payments::payments.paymentGatewayList');
	$paymentGateways = array();
	foreach ($enabledPaymentGateways as $gatewayName){
		$paymentGateways[] = Laravel\IoC::resolve($gatewayName);
	}
	return new DefaultPaymentManager($paymentGateways);
});