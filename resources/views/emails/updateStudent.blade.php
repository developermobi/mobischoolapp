 <!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
</head>

<body>
	
	<p>Hello {{ $userInfo['name'] }},</p>
	<p>Your updated details are</p>
	<p>User Name : {{ $userInfo['email'] }} / {{ $userInfo['mobile'] }}</p>
	<p>Password : {{ $userInfo['password'] }}</p>

	<br>
	<p>Regards,</p>
	<p>Mobisoft Technology</p>
	<!-- <img src="{{ env('APP_URL') }}/images/abi_logo.png')}}" width="60%"> -->
	
</body>

</html> 