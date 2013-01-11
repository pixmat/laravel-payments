<?php
use Laravel\Log;
use Laravel\IoC;
use Laravel\Autoloader;
use Laravel\Bundle;


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
IoC::register('paymentdao', function(){
	return null;
});

IoC::register('invoicedao', function(){
	return null;
});

IoC::singleton('configs', function()
{
	Log::debug('loading configurations into IoC');
	return Configuration::build();
});

IoC::register('paguelofacil', function()
{
	//TODO is there any way to load this according to configs and avoid manual registration of gateways
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

