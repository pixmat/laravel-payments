<?php

use Laravel\Log;

class Payments_Test_Controller extends Controller
{
	
	public function action_paguelofacil(){
		Log::debug('test for pagueloffacil');
		$this->layout->content = View::make("payments::paguelofacil");
	}
}