<?php
use Laravel\Routing\Route;


/**
 * Routes
 */
Route::get('(:bundle)', "payments::payments@index");

Route::get('(:bundle)/test/paguelofacil', array(
	'as' => 'payments_test_paguelo_facil',
	'uses' => 'payments::test@pagueloFacil',
));
