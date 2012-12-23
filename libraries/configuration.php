<?php
use Laravel\Config;

class Configuration extends DataValue
{
	public function __construct(array $config = null)
	{
		if(is_null($config)){
			throw new Exception('You need to provide a valid not null configs array');
		}
		parent::__construct($config);
	}
	
	public static function build(){
		return new Configuration(Config::get('payments::payments'));
	}
}