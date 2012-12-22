<?php

Autoloader::namespaces(array(
'Payments' => Bundle::path('laravel-payment') . 'libraries',
'Payments\Models' => Bundle::path('laravel-payment') . 'models',
));
/*
 Laravel\IoC::register('paypal', function()
 {
 		$config = Config::get('payments::payments.paypal');
 		$paypalGateway = new PaypalGateway($config);
 		return $paypalGateway;
 		});
*/
Laravel\IoC::register('paguelofacil', function()
{
	Log::debug('Storing into IoC container a resolver for paguelofacil');
	$config = Config::get('payments::payments.paguelofacil');
	return new PagueloFacilGateway($config);
});

/**
 * Register IoC objects
*/
Laravel\IoC::singleton('paymentManager', function()
{
	Log::debug('creating payment manager');
	$enabledPaymentGateways = Config::get('payments::payments.paymentServicesList');
	$paymentGateways = array();
	$count = count($enabledPaymentGateways);
	Log::debug("$count payment gateways enabled");
	foreach ($enabledPaymentGateways as $gatewayName){
		Log::debug("Resolving $gatewayName in IoC container");
		$paymentGateways[] = Laravel\IoC::resolve($gatewayName);
	}
	$manager = new DefPaymentManager($paymentGateways);
	return $manager;
});
