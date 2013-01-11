<?php
interface InvoiceDao
{
	function findByHashKey($hash);
}