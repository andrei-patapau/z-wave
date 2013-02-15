<?php

$iphone = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");

if ($iphone == false){
	//Non iPhone
	header("Location: /index.php"); 
} else {
	//iPhone HERE
}

?>

<!DOCTYPE html>
<html>
<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet"  href="css/themes/default/jquery.mobile-1.1.1.css" />
<script src="js/jquery.js"></script>
<script src="docs/_assets/js/jqm-docs.js"></script>
<script src="js/jquery.mobile-1.1.1.js"></script>

   
   <script>
function checkLogin()
{	

	var uname = $('#myusername').val();
	
	var pass = $('#mypassword').val();
	
	
	
	$.post("../checklogin.php", { action: "checkLogin", myusername: uname, mypassword: pass},
		function(data) {
			if(data.indexOf("Failed") > 0){

				$('#status').html(data);
			}
			else{
				window.location = "/i/main.php";
			}
		});
	
}

   </script>
</head>
<body>


<div data-role="page">

    <div data-role="header">
    <h1>Z-Wave Login</h1>
    </div><!-- /header -->
    
    <div data-role="content" align="center">	
    
    
    <div data-role="fieldcontain">
     <label for="myusername">Login</label>
     <input type="text" id="myusername" name="ident" placeholder="Your login" value="root">
    </div>

    <div data-role="fieldcontain">
     <label for="mypassword">Password</label>
     <input type="password" id="mypassword" name="password" placeholder="Your password" value="betahomes">
    </div>
	<br>
    <button data-inline="true" onClick="checkLogin()">Login</button>

<div id="status" style="color:red"></div>



	</div><!-- /content -->

	<div data-role="footer" style="POSITION: absolute; BOTTOM: 0px; WIDTH: 100%;">
		<h4>BetaHomes &copy; 2012</h4>
	</div><!-- /footer -->
</div><!-- /page -->
   
   
</body>
</html>