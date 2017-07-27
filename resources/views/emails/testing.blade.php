 <!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
</head>

<body>

	<p>Dear <b>{{ $userInfo['name'] }},</b></p>

	<p>Your seat has been booked.</p>	
	
	<p>Booked On : {{ $userInfo['booked_date'] }}</p> 

	<table width="40%" style="background-color: rgba(204, 204, 204, 0.19);border: 1px solid black;">
		<tr>
			<td style="height: 166px;" colspan="2"><img src="{{ env('APP_URL') }}/ShowImage/{{ $userInfo['show_image'] }}" width="100%" height="100%"></td>
		</tr>
		<tr><td colspan="2" style="padding: 10px;"><b>{{ $userInfo['show_name'] }}</b></td></tr>
		<tr><td style="padding: 10px;"><span style="font-size: 14px;color: #8f8b8b;">Date</span><br> {{ $userInfo['show_date'] }}</td><td><span style="font-size: 14px;color: #8f8b8b;">Time</span><br> {{ $userInfo['show_time'] }}</td></tr>
		<tr><td style="padding: 10px;"><span style="font-size: 14px;color: #8f8b8b;">Theater</span><br>{{ $userInfo['hall_name'] }}, {{ $userInfo['location'] }}</td><td><span style="font-size: 14px;color: #8f8b8b;">Seats</span><br>{{ $userInfo['seat_name'] }}</td></tr>
		<tr><td style="padding: 10px;" colspan="2"><hr></td></tr>
		<tr><td style="padding: 10px;"><span style="font-size: 20px;font-weight: 600;">Aage Badho India</span></td><td style="padding: 10px;"><img src="{{ env('APP_URL') }}/images/abi_logo_sm.png" style="height: 58px;width: 100%;"></td></tr>
	</table>
	
</body>

</html> 