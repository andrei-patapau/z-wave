
<?php
session_start();

if(!isset($_SESSION["myusername"])){
header("location:/Logout.php");//clean session and return to index.php
}
?>

<!DOCTYPE html> 
<html>
<head>
<meta charset="utf-8">
<title>Lights</title>

<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet"  href="css/themes/default/jquery.mobile-1.1.1.css" />
<link rel="stylesheet" href="docs/_assets/css/jqm-docs.css" />
<script src="js/jquery.js"></script>
<script src="docs/_assets/js/jqm-docs.js"></script>
<script src="js/jquery.mobile-1.1.1.js"></script>
<!--<link href="styles/custom.css" rel="stylesheet" type="text/css">-->
<script src="loadLights/zwave_scripts.js"></script>
</head> 
<script>

function getPortNumber()
{
	$.post("loadLights/data.php", { action: "getPortNumber"},
		function(data) {
			alert("Data Loaded: " + data);
		});
}

//-----------------------------------------------------------------------------
function frameLoadSocket()
{	
	$("#container").load("loadLights/data.php", {action: "frameLoadSocket"});

}

//-----------------------------------------------------------------------------

function frameLoadLight()
{	
	$("#container").load("loadLights/data.php", {action: "frameLoadLight"});

}

//-----------------------------------------------------------------------------

function frameExecute(id)
{	
	$.post("loadLights/data.php", { action: "frameExecute", command: id},
		function(data) {
			$('#container2').html(data);
		});
}

//-----------------------------------------------------------------------------
//Reload Frames on Page Refresh
$(document).ready(function() {
	window.onload(frameLoadSocket());
});
</script>


<body>
<!----------------------------------------------------------------------------------------------------->

<div id="container"></div>



</body>
</html>