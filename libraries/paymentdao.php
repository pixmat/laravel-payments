<?php
interface PaymentDao
{
	
	function fromPaymentGatewayResult(array $result);
}