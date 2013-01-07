<?php

interface IPaymentResult
{
	const RECORDED_KEY = 'recordedKey';
	const RECORDED_AMOUNT = 'recordedAmount';
	const RECORDED_DATE = 'recordedDate';
	const RECORDED_TIME = 'recordedTime';
	const RECORDED_STATUS = 'recordedStatus';
	const RECORDED_CLIENT_NAME = 'clientName';
	const RECORDED_TYPE = 'type';
	const RECORDED_CLIENT_EMAIL = 'clientEmail';
	const RECORDED_GATEWAY_NAME = 'paymentGateway';
	const SUCCESSFUL = 'successful';
	const FAILED = 'failed';
}