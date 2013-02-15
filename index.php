
<?php

$iphone = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");

if ($iphone == true){
	//iPhone
	header("Location: i/index.php"); 
} else {
	//Non iPhone HERE
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("includes/imports.php"); ?>
<script type="text/javascript"> 
document.onkeypress = keyPress;
function keyPress(e){ 
	var x = e || window.event; 
	var key = (x.keyCode || x.which); 
	if(key == 13){ 
		checkLogin();
	} 
}
</script> 
<title>BetaHomes</title>
</head>
<body>

<div class="container" style="border-style:groove;" align="center">

<?php include("includes/header_login.php"); ?>
	
    
<img style="margin-bottom:20px;" src="../images/logo.jpg">
<fieldset class="ui-corner-all" style="width:500px; ">

	<legend align="center"><h1 style="color: #439deb;">USER LOGIN</h1></legend>
	
	<table width="270" style="border-style:none;margin-top:30px;">
	<tr>
	<td>
		<table width="100%" style="background-color:#F8F8F8;color: #000000;">
			<tr>
				<td width="78"><b>Username</b></td>
				<td width="6"><b>:</b></td>
				<td width="294"><input id="myusername" name="myusername" type="text"></td>
			</tr>
			<tr>
				<td><b>Password</b></td>
				<td><b>:</b></td>
				<td><input id="mypassword" name="mypassword" type="password"></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td align="left"><button onclick="checkLogin()"><b style="color:#000000">Login</b></button></td>
			</tr>
		</table>
	</td>
	</tr>
	</table>
	<br>
	<div id="status" style="color:red"></div>
</fieldset>


<br><br>

<!-- end .container --></div>

<?php include("includes/footer.php"); ?>

</body>
</html>
