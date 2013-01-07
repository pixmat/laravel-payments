<?php

interface PaymentService
{
	function name();
	function paymentLink(IInvoice $invoice);
	function buttonImage();
	function processResult(DataValue $response);
}
