 <!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
</head>

<body>
	<!-- $userInfo['name'] -->
	

	<p>Dear <b>{{ $userInfo['guestName'] }},</b></p>

	<p>We Verified your mobile Number, Find below login Details.</p>

	<p>Username:{{$userInfo['guestEmail']}}</p>
	<p>password: {{ $userInfo['password'] }}</p>
	
	<br>
	
    </br></br>

	<br>
	<img src="{{ env('APP_URL') }}/images/abi_logo.png')}}" width="60%">
	
	
</body>

</html> 