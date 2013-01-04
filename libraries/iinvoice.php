<?php

interface IInvoice {
	function invoiceId();
	function clientName();
	function description();
	function amount();
	function isRecurrent();
	function hashKey();
}
