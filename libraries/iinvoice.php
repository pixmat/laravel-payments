<?php

interface IInvoice {
	function invoiceId();
	function clientName();
	function description();
	function amount();
	function isRecurrent();
	function hashKey();
	function settlementDate();
	function maturityDate();
	function subtotalAmount();
	function taxesAmount();
	function totalAmount();

	/**
	 * Is an outstanding bill, not yet overdue or paid.
	 * When isOutstanding return true, isOverdue and isPaid must return false
	 * 
	 * @return true if this invoice is valid, maybe because the maturity date has not been reached yet, or false otherwise
	 */
	function isOutstanding();
	
	/**
	 * Is a bill that is being processed, meaning is not paid yet.
	 * When isPendingApproval return true, isOutstanding or isOverdue may return true but not both of them, 
	 * meanwhile isPaid must return false.
	 */
	function isPendingApproval();
	
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
	

	/**
	 * Pay this invoice, effectively registering the payment into permanent storage (DB, file, etc) and update this
	 * invoice status.
	 * 
	 * @param unknown $payment the payment information
	 */
	public function paybill($payment);
}
