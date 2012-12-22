<?php
use Laravel\Routing\Route;
/**
 * Filters
 */
require_once __DIR__ . '/routes_filters.php';

/**
 * View Composers
 */
require_once __DIR__ . '/routes_view_composers.php';

/**
 * Routes
 */
Route::get('(:bundle)', "payments::payments@index");

Route::get('(:bundle)/test/paguelofacil', array(
	'as' => 'payments_test_paguelo_facil',
	'uses' => 'payments::test@pagueloFacil',
));
