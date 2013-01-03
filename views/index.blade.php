<h3>Available payment services</h3>
<ul>
	@foreach($paymentManager->getPaymentGateways() as $service)
	<li>{{ $service->name() }}</li> 
	@endforeach
</ul>
