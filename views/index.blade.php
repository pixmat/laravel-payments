
<h3>Available payment services</h3>
<ul>
@foreach($service in $paymentManager->getPaymentGateways())
	<li>{{ $service->name() }}</li>
@endforeach
</ul>
