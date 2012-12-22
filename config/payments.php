<?php
return array(
		//test or production
		'mode' => 'test',
		 //List of enabled payment gateways
		'paymentGatewayList' => array (
				'payments.paypal',
				'payments.pagueloFacil',
		),
		
		'pagueloFacilConfig' => array(
				'cclw' => '==EPICENTRO==',
				//'paymentUrl' => 'https://secure.paguelofacil.com/LinkDeamon.cfm',
				'paymentUrl' => 'http://epicentro.local/payments/test/paguelofacil',
		),

		'paypalConfig' => array(
				'api_username' => "iambrs_1298074268_biz_api1.gmail.com",
				'api_password' => "1298074286",
				'api_signature' => "Awe05O9DgD-XyAL3-HsFoqNs..1VAOncRYkwEN.LCh-94svEO5c0i0Ar",
				'paymentUrl' => 'http://epicentro.local/payments/test/paypal',
		),
);
