<?php
use Laravel\Log;
use Laravel\IoC;


Log::debug('== starting payments bundle ==');
Autoloader::directories(array(
Bundle::path('payments') . 'libraries',
));

Autoloader::namespaces(array(
'Payments' => Bundle::path('payments'),
));


/**
 * Register IoC objects
*/
/*
 Laravel\IoC::register('paypal', function()
 {
 		$config = Config::get('payments::payments.paypal');
 		$paypalGateway = new PaypalGateway($config);
 		return $paypalGateway;
 		});
*/
IoC::register('configs', function()
{
	Log::debug('loading configurations into IoC');
	return Configuration::build();
});

IoC::register('paguelofacil', function()
{
	$configs = IoC::resolve('configs');
	Log::debug('creating paguelofacil payment gateway');
	return new PagueloFacilGateway($configs->paguelofacil);
});

IoC::singleton('paymentManager', function()
{
	$configs = IoC::resolve('configs');
	Log::debug('creating payment manager');
	return  new DefaultPaymentManager($configs->paymentServicesList);
});

