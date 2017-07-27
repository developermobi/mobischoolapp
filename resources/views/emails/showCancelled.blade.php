 <!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
</head>

<body>
<?php
	$name=$showInfo['name'];
	$show_name=$showInfo['show_name'];
?>
	<p> Dear {{$name}}},</p>
	<p>Your refund of cancelled show {{show_name}} has been successfully done. Please check your ABI account details.</p>
	<p>Thank you,</p>
	<p>Aage Badho India</p>

	<table width="40%" style="background-color: rgba(204, 204, 204, 0.19);border: 1px solid black;">
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