<?php
use Laravel\View;

/**
 * Add all common data to views
 * 
 * @see http://laravel.com/docs/views#view-composers
 */
View::composer(array('payments::index', 'payments::wellcome', 'payments::payment'), function(View $view)
{
	Log::debug('Composing view ' . $view->view);
	$paymentManager = IoC::resolve('paymentManager');
	$view->data['paymentManager'] = $paymentManager;
});