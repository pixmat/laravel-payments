<?php
/**
 * laravel payments routes
 */
//list available payment gateways
Route::get('(:bundle)', 'payments::payments@chooseMethod');
//process payment response
Route::any('(:bundle)/process/(:any)', 'payments::payments@processPayment');

//dummy payment testing (if you do not have a valid test account)
Route::get('(:bundle)/test/paguelofacil', array(
	'as' => 'payments_test_paguelo_facil',
	'uses' => 'payments::test@pagueloFacil',
));


