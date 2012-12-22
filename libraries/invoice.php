<?php

interface Invoice {
	function invoiceId();
	function clientName();
	function description();
	function amount();
	function isRecurrent();
	
}
