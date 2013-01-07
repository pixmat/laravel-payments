<h3>Available payment services</h3>
<ul>
	@foreach($paymentManager->getPaymentGateways() as $service)
	<li>
		<a href="{{ $service->paymentLink }}"> {{ $service->name() }} the
			link is: {{ $service->paymentLink }}
			<img alt="{{ $service->name() }}" src="{{ $service->buttonImage() }}"/> 
		</a>
	</li>
	<!-- service button -->
	@endforeach
</ul>
