 <!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
</head>

<body>
	<!-- $userInfo['name'] -->
	

	<p>Dear <b>{{ $userInfo['name'] }},</b></p>

	<p>Your package have been renewed.</p>

	<p>Package:</p>
	<p>Amount: {{ $userInfo['amount'] }}</p>
	<p>Number of seat: {{ $userInfo['no_of_seat'] }}</p>
	<p>Number of show: {{ $userInfo['no_of_show'] }}</p>
	<p>Validity: {{ $userInfo['validity'] }}</p>
    </br></br>

	<br>
	<img src="{{ env('APP_URL') }}/images/abi_logo.png')}}" width="60%">
	
	
</body>

</html> 