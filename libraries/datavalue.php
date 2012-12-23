<?php

class DataValue
{
	private $data = array();

	public function __construct(array $data = null)
	{
		if(is_null($data)){
			throw new Exception('You need to provide a valid not null array');
		}
		$this->setData($data);
	}
	
	public function __set($name, $value)
	{
		$this->data[$name] = $value;
	}

	public function __get($name)
	{
		if(array_key_exists($name, $this->data)) {
			return $this->data[$name];
		}

		$trace = debug_backtrace();
		trigger_error('Property not found: ' . $name .
		'file: ' . $trace[0]['file'] . ' at line ' . $trace[0]['line'],
		E_USER_NOTICE);
		return null;
	}

	public function __isset($name)
	{
		return isset($this->data[$name]);
	}

	public function __unset($name)
	{
		unset($this->data[$name]);
	}

	protected function setData(array $data)
	{
		$this->data = $data;
	}
}