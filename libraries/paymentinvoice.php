<?php

interface PaymentInvoice {
	function invoiceId();
	function clientName();
	function description();
	function amount();
	function isRecurrent();
	
}
