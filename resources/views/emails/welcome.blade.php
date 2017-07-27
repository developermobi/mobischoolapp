 <!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
</head>

<body>
	<!-- $userInfo['name'] -->
	

	<p>Dear <b>{{ $userInfo[0]->name }},</b></p>

	<p>You have been register with Aagebadho India.</p>

	<p>Account Details:</p>
	<p>User Name: {{ $userInfo[0]->email }}</p>
	<p>Password: {{ $userInfo[0]->password }}</p>
	<br>
	<p>Package:</p>
	<p>Plan ID: {{ $userInfo[0]->plan_id }}</p>
	<p>Plan Name: {{ $userInfo[0]->plan_name }}</p>
	<p>Amount: {{ $userInfo[0]->amount }}</p>
	<p>Validity: {{ $userInfo[0]->validity }}</p>
	
	<table style="width:100%;border: 1px solid black;border-collapse: collapse;">  
	  <tr>  
	    <th style="padding: 5px;text-align: left;border: 1px solid black;">Product Name</th>
	    <th style="padding: 5px;text-align: left;border: 1px solid black;">No of Show</th>  
	    <th style="padding: 5px;text-align: left;border: 1px solid black;">No of Seat</th>  
	  </tr>  

  	@foreach ($userInfo as $userInfo)
  		<tr>  
		    <td style="padding: 5px;text-align: left;border: 1px solid black;">{{ $userInfo->product_name }}</td>  
		    <td style="padding: 5px;text-align: left;border: 1px solid black;">{{ $userInfo->no_of_show }}</td>  
		    <td style="padding: 5px;text-align: left;border: 1px solid black;">{{ $userInfo->no_of_seat }}</td>  
		</tr>  
	@endforeach
	  
	</table>  
    </br></br>

	<br>
	<img src="{{ env('APP_URL') }}/images/abi_logo.png')}}" width="60%">
	
	
</body>

</html> 