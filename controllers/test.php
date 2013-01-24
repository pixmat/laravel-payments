<?php

use Laravel\View;

use Laravel\URL;

use Laravel\Log;
use Laravel\Redirect;
use Laravel\Error;
use Laravel\HTML;

class Payments_Test_Controller extends Controller
{
	public function action_processPayment($paymentGateway){
		parse_str($_SERVER['QUERY_STRING'], $queryString);
		Log::debug('Test query string: ' . print_r($queryString, true));
		Log::debug("test for $paymentGateway");
		if($paymentGateway === 'paguelofacil'){
			$responseQueryString = $this->getPagueloFacilSuccessResponse(new DataValue($queryString));
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
	public function getPagueloFacilSuccessResponse(DataValue $query)
	{
		$result = array(
				'TotalPagado' => $query->CMTN,
				'Fecha' => date('Y-m-d'),
				'Hora' => date('H:i:s'),
				'Tipo' => 'VISA',
				'Oper' => date('YmdHis'),
				'Usuario' => 'Joker',
				'Email' => 'joker@gotham.city',
				'Estado' => 'Aprobada',
				'invoice' => $query->invoice,
		);
		return $result;
	}
}