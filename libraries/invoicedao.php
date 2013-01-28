<?php
interface InvoiceDao
{
	/**
	 * Search an return an IInvoice instance using the given hash string
	 * 
	 * @param string $hash the invoice unique hash
	 * @return an IInvoice implementation
	 */
	function findByHashKey($hash);
}