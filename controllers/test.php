<?php

use Laravel\Log;

class Payments_Test_Controller extends Controller
{
	var $layout = "payments::layouts.default";
	
	public function action_paguelofacil(){
		Log::debug('test for pagueloffacil');
		$view = View::make("payments::paguelofacil", array(
				'paymentManager' => $paymentManager,
		));
		$this->layout->content = $view;
	}
}