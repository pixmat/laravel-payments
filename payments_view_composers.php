<?php
use Laravel\View;

/**
 * Add all common data to views
 * 
 * @see http://laravel.com/docs/views#view-composers
 */
View::composer(array('payments::index', 'payments::paguelofacil'), function(View $view)
{
	Log::debug('Composing view ' . $view->view);
	$paymentManager = IoC::resolve('paymentManager');
	$view->data['paymentManager'] = $paymentManager;
});