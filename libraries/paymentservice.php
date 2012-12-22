<?php

interface PaymentService
{
	function paymentLink(PaymentInvoice $paymentInvoice);
}
