<?php
use Laravel\Routing\Route;

/**
 * laravel payments routes
 * You must define the logged_in filter
 */
Route::group(array('before' => 'logged_in'), function()
{
	//process payment response
	Route::any('(:bundle)/process/(:any)', array(
		'as' => 'process_payment_response',
		'uses' => 'payments::payments@processPayment'
	));
	
	//list available payment gateways
	Route::get('(:bundle)/(:any)/(:any?)', array(
		'as' => 'choose_payment_method',
		'uses' => 'payments::payments@chooseMethod',
	));

	//dummy payment testing (if you do not have a valid test account)
	Route::get('(:bundle)/test/process/(:any)', array(
		'as' => 'dummy_payments_processor',
		'uses' => 'payments::test@processPayment',
	));

});
