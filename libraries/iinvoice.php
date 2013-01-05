<?php

interface IInvoice {
	function invoiceId();
	function clientName();
	function description();
	function amount();
	function isRecurrent();
	function hashKey();
	
	/**
	 * Is an outstanding bill, not yet overdue or paid.
	 * When isOutstanding return true, isOverdue and isPaid must return false
	 * 
	 * @return true if this invoice is valid, maybe because the maturity date has not been reached yet, or false otherwise
	 */
	function isOutstanding();
	
	/**
	 * overdue bill
	 * When isOverdue return true, isOutstanding and isPaid must return false
	 * 
	 * @return true if no payment has been received for this invoice and the maturity has been reached or false otherwise
	 */
	function isOverdue();
	
	/**
	 * paid bill
	 * When isPaid return true, isOutstanding and isOverdue must return false
	 * 
	 * @return true is the bill has been paid
	 */
	function isPaid();
}
