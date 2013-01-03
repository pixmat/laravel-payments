<?php
/**
 * laravel payments routes
 */
Route::get('(:bundle)', 'payments::payments@index');
Route::get('(:bundle)/wellcome', 'payments::payments@wellcome');
Route::get('(:bundle)/payment', 'payments::payments@payment');

Route::get('(:bundle)/test/paguelofacil', array(
	'as' => 'payments_test_paguelo_facil',
	'uses' => 'payments::test@pagueloFacil',
));


