<?php
/**
 * Add all common data to views
 * 
 * @see http://laravel.com/docs/views#view-composers
 */
View::composer('payments::index', 'payments::wellcome', 'payments::payment', function($view)
{
	//$view->author = 'Eivar';
	$paymentManager = IoC::resolve('paymentManager');
	$view->paymentManager = $paymentManager;
});