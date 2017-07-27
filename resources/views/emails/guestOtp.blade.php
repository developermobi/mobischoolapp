 <!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
</head>

<body>
	<!-- $userInfo['name'] -->
	

	<p>Dear <b>{{ $userInfo['guestName'] }},</b></p>

	<p>We need to verify You Mobile Number by entering the OTP provided below.</p>

	<p>Your OTP For Guest Login:</p>
	<p>OTP: {{ $userInfo['otp'] }}</p>
	
	<br>
	
    </br></br>

	<br>
	<img src="{{ env('APP_URL') }}/images/abi_logo.png')}}" width="60%">
	
	
</body>

</html> 