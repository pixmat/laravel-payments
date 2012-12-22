<?php
return array(
		'name' => 'payments',
		//test or production
		'mode' => 'test',

		//List of enabled payment gateways
		'paymentServicesList' => array (
				//'paypal',
				'paguelofacil',
		),
		'paguelofacil' => array(
				'name' => 'Paguelo Facil',
				'cclw' => '==EPICENTRO==',
				//'paymentUrl' => 'https://secure.paguelofacil.com/LinkDeamon.cfm',
				'paymentUrl' => 'http://epicentro.local/payments/test/paguelofacil',
		),

		'paypal' => array(
				'name' => 'Paypal',
				'api_username' => "iambrs_1298074268_biz_api1.gmail.com",
				'api_password' => "1298074286",
				'api_signature' => "Awe05O9DgD-XyAL3-HsFoqNs..1VAOncRYkwEN.LCh-94svEO5c0i0Ar",
				'paymentUrl' => 'http://epicentro.local/payments/test/paypal',
		),
);
