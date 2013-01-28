<?php

use Laravel\Config;

use Laravel\View;

use Laravel\URL;

use Laravel\Log;
use Laravel\Redirect;
use Laravel\Error;
use Laravel\HTML;

class Payments_Test_Controller extends Controller
{

	/**
	 * Fake various payment gateways response using only internal laravel redirections
	 *
	 * @param unknown $paymentGateway
	 * @return In case of unknown payment service a view will be shown indicating that no test can process can be performed
	 */
	public function action_processPayment($paymentGateway){
		$chancesToSuccess = Config::get('main.test_changes_2_success');
		parse_str($_SERVER['QUERY_STRING'], $queryString);
		Log::debug('Test query string: ' . print_r($queryString, true));
		Log::debug("running test for $paymentGateway");
		if($paymentGateway === 'paguelofacil'){
			$responseQueryString = $this->getPagueloFacilVariableResponse(new DataValue($queryString), $chancesToSuccess);
			$url = EpiUrl::to_action('payments::payments@processPayment', array('paguelofacil'), $responseQueryString);
			Log::debug("redirecting to url: $url");
			return Redirect::to($url);
		}
		Return View::make('payments::notestfound', array('paymentGateway'=>$paymentGateway));
	}

	/**
	 * Faking paguelo facil response, that according to documentation must contain the following fields:
	 *
	 * TotalPagado = 0 si denegada, el monto cobrado si es aceptada
	 * Fecha =Fecha de la transacción en formato dd/mm/yyyy
	 * Hora = Hora de la transacción en formato HH:MM.SS
	 * Tipo = Tipo de tarjeta VISA o MC para MasterCard
	 * Oper = Numero de Operación alfanumérico 15 caracteres
	 * Usuario = Nombre y Apellidos del tarjeta habiente
	 * Email = Email del tarjetahabiente
	 * Estado = Aprobada o Denegado
	 *
	 * plus any extra field send to payment request
	 *
	 * @param DataValue $query
	 */
	public function getPagueloFacilVariableResponse(DataValue $query, $chance = 50)
	{
		$result = array(
				'TotalPagado' => $query->CMTN,
				'Fecha' => date('Y-m-d'),
				'Hora' => date('H:i:s'),
				'Tipo' => 'VISA',
				'Oper' => date('YmdHis'),
				'Usuario' => 'Joker',
				'Email' => 'joker@gotham.city',
				'invoice' => $query->invoice,
		);
		$result['Estado'] = $this->randBool($chance) ? 'Aprobada' : 'Denegada';
		return $result;
	}

	/**
	 * Function to simulate a chance to get a positive response.
	 * $chance param must be between 1 and 100, if null or number out of range is provided then 50 will be used
	 * @param number $chance a valid number between 1 and 100
	 * @return boolean
	 */
	private function randBool($chance = 50) {
		if($chance < 1 || $chance > 100) $chance = 50;
		Log::debug("chances to success are: $chance");
		return (rand(1, 100) <= $chance);
	}

}