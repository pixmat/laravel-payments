<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>{{ Config::get('administrator::administrator.title') }}</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
</head>
<body>
	@include('payments::partials.header')
	<div class="container">{{ $content }}</div>
	@include('payments::partials.footer')
</body>
</html>
