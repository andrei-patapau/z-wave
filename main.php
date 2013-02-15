<?php
session_start();

if(!isset($_SESSION["myusername"])){
header("location:/i/iLogout.php");//clean session and return to index.php
}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php require_once 'connection.php';  //style="font-size:62.5%;" ?>

<?php include("includes/imports.php"); ?>
<script type="text/javascript" 
src="/includes/zwave_scripts.js">
</script>


<script>
$(document).ready(function() {
	$('#clickme').click(function() {
	  $('#cluster').slideToggle('slow', function() {
		// Animation complete.
	  });
	});
	
	$('#cluster').click(function() {
	  $('#cluster').slideToggle('slow', function() {
		// Animation complete.
	  });
	});
});

//hide unused buttons
$(document).ready(function() {
	$('#execPerlON').hide();
	$('#execPerlOFF').hide();
	$('#getPortNumber').hide();
	//$('#execPerlON').attr("disabled", true);
	//$('#execPerlOFF').attr("disabled", true);
});

</script>


<title>BetaHomes</title>
</head>
<body>
<div id="wrapper">
	<div class="container" style="border-style:groove;">
		<?php include("includes/header.php"); ?>
		<!-- START BODY OF CONTAINER WITHIN CENTER TAG -->
			<img id="clickme" style="margin:0 491px;" src="/images/marker_2.jpg" alt="" width="17px" height="17px" />
			<div id="divBorder">
<!----------------------------------------------------------------------------------------------------------------->



<div id="cluster" style="margin:20px auto;">
<img id="book" style="display:inline;float:left;margin-top:-35px;" src="/images/title900.png" alt="" width="970px" height="184px" />
<div id="dbCheckVal_2" style="display:inline;float:right;margin-left:20px;margin-top:25px;" value="not pass"></div>
</div>


<!--<button style="margin:10px 20px;" id="frameReload" onclick="frameLoad()">&#8595;&nbsp;Show Nodes&nbsp;&#8595;</button>-->
<fieldset align="center" width="100%" style="margin:10px 0; padding:15px 20px;">
<legend><a onClick="frameLoad()" style="background:url(/images/reload50x50.png) no-repeat;display:block;width:50px;height:50px;cursor: pointer;"></a><!-- end .header --></legend>

<div id="container"></div>

</fieldset>

<!--
<br><br>
<div id="container2">----</div>


<button id="startServer_2" onclick="startServer_2()">Start SERVER 2</button>
<div id="startServer_2_" style="background-color:#F5F5F5;"></div>
<br><br>



<fieldset align="center" width="100%" style="margin:10px 20px; padding:15px 20px;">
<legend>Database table 'nodes'</legend>
<div align="center" id="dbCheckVal" style="background-color:#F5F5F5;width:850px;" value="not pass"></div>
</fieldset>
<br><br>


<div align="center" width="100%" style="margin:10px 20px; padding:15px 20px;">

<br><br>
<button id="execPerlON" onclick="execPerlON()">On</button>

<button id="execPerlOFF" onclick="execPerlOFF()">Off</button>

<button id="getPortNumber" onclick="getPortNumber()">Port</button>

<button id="viewPort" onclick="viewPort()">View Port</button>

<button id="read_log" onclick="read_log()">Read Log</button>
<br><br>

</div>




<br><br> Who Am I (user name): 
<b><?php system("whoami"); ?></b>
-->
<!----------------------------------------------------------------------------------------------------------------->
			</div>
		<!-- END BODY OF CONTAINER WITHIN CENTER TAG -->
	</div><!-- end .container -->
	<div class="push"></div>
</div><!-- end #wrapper -->
<?php include("includes/footer.php"); ?>

</body>
</html>
