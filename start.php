<?php

Autoloader::namespaces(array(
	'Payments' => Bundle::path('laravel-payment') . 'libraries',
	'Payments\Models' => Bundle::path('laravel-payment') . 'models',
));

/*Laravel\IoC::register('paypalGateway', function()
{
	$paypalGateway = new PaypalPaymentGateway();
	return $paypalGateway;
});
*/
Laravel\IoC::register('pagueloFacilGateway', function()
{
	$config = Config::get('payments::payments.pagueloFacilConfig');
	return new PagueloFaciltGateway($config);
});

/**
 * Register IoC objects
*/
Laravel\IoC::singleton('paymentManager', function()
{
	$enabledPaymentGateways = Config::get('laravel-payments::laravel-payments.paymentGatewayList');
	$paymentGateways = array();
	foreach ($enabledPaymentGateways as $gatewayName){
		$paymentGateways[] = Laravel\IoC::resolve($gatewayName);
	}
	return new DefaultPaymentManager($paymentGateways);
});