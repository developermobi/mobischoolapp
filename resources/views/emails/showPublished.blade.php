 <!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
</head>

<body>
<?php
	$show_timefrom=strtotime($showInfo['show_time']);
	$show_timefrom=date('h:i A',$show_timefrom);
?>
	<p> DEAR {{ $showInfo['name'] }},</p>
	<p>ONLINE BOOKING HAS BEEN STARTED, PLEASE CHECK UPDATES.</p>
	<p>NOTE : PHONE BOOKING 1 DAY BEFORE THE SHOW BETWEEN 3PM TO 6PM.</p>

	<table width="40%" style="background-color: rgba(204, 204, 204, 0.19);border: 1px solid black;">
		<tr>
			<td style="height: 166px;" colspan="2"><img src="{{ env('APP_URL') }}/ShowImage/{{ $showInfo['show_image'] }}" width="100%" height="100%"></td>
		</tr>
		<tr>
			<td  style="padding: 10px;"><b>{{ $showInfo['show_name'] }}</b></td>
			<td  style="padding: 10px; float:right;"><b>{{ $showInfo['show_date'] }}</b></td>
		</tr>
		<tr>
			<td  style="padding: 10px;"><b>{{ $showInfo['hall_name'] }}</b><br><b>({{ $showInfo['location'] }})</b></td>
			<td  style="padding: 10px; float:right;"><b>{{ $show_timefrom }}</b></td>
		</tr>
		<tr><td style="padding: 10px;" colspan="2"><hr></td></tr>
		<tr>
			<td style="padding: 10px;"><span style="font-size: 20px;font-weight: 600;">Aage Badho India</span></td>
			<td style="padding: 10px;"><img src="{{ env('APP_URL') }}/images/abi_logo_sm.png" style="height: 58px;width: 100%;"></td>
		</tr>
	</table>
	
	</br></br>
	<p><b>Regards,</b></p>
	<p><b>Aage Badho India</b></p>
</body>

</html> 