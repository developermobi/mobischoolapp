 <!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
</head>

<body>
	
	

	<p>Dear {{$userInfo[0]->name}}<b></b></p>

	<p>Your Payment is Successfull .You have been registered as a member with Aagebadho India .</p>
	<p>Login Details</p>
	<p>Username:- {{$userInfo[0]->email}}</p>
	<p>Password:- {{$userInfo[0]->password}}</p>
	<p>Your Payment Details will be forwarded to your email shortly.</p>

	
	<br>
	
	
	
    </br></br>

	<br>
	<img src="{{ env('APP_URL') }}/images/abi_logo.png')}}" width="60%">
	
	
</body>

</html> 