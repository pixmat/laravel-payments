<?php

interface PaymentService
{
	function name();
	function paymentLink(Invoice $invoice);
	function buttonImage();
}
