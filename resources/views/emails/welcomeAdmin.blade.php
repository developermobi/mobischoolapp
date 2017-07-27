 <!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
</head>

<body>
	<!-- $userInfo['name'] -->
	

	<p>Dear <b>{{ $userInfo['name'] }},</b></p>

	<p>You have been register as a admin with Aage Badho India.</p>

	<p>Account Details:</p>
	<p>User Name: {{ $userInfo['email'] }}</p>
	<p>Password: {{ $userInfo['password'] }}</p>
	<p>Branch: {{ $userInfo['branch'] }}</p>
	<p>Click <a href="{{ env('APP_URL') }}/abiAdmin">here</a> to login.</p>
    </br></br>

	<br>
	<img src="{{ env('APP_URL') }}/images/abi_logo.png" width="60%">
	
	
</body>

</html> 