
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


<body>
<!----------------------------------------------------------------------------------------------------->

<div id="container"></div>

<!------------------------------------------------------------------------------------------>
<!--- Main Lights Page -->
<div id="main" class="all_divs">

	<div data-role="header">
    	<!--<a data-rel="back" data-icon="arrow-l">Back</a>-->
		<a href="main.php" rel="external" data-icon="arrow-l">Back</a>
		<h1>Lights</h1>
        <a href="iLogout.php" rel="external" data-icon="delete" class="ui-btn-right">Logout</a>
	</div>

	<div id="mainContent">	
		<ul data-role="listview" data-inset="true" style="margin:10px;">
			<li><a id="liid1">Node Name 1</a></li>
            <li><a id="liid2">Node Name 2</a></li>
		</ul>
	</div>
    
	<div data-role="footer" style="POSITION: absolute; float:bottom; BOTTOM: 0; WIDTH: 100%; TEXT-ALIGN: center;">
		<h4>BetaHomes &copy; 2012</h4>
	</div>
</div>

<!------------------------------------------------------------------------------------------>
<!--- Light 1 -->
<div id="nodeid1" class="all_divs">
 
	<div data-role="header" >
    	<a class="backMain" data-icon="arrow-l">Back</a>
		<h1>Living Room</h1>
        <a href="iLogout.php" rel="external" data-icon="delete" class="ui-btn-right">Logout</a>
	</div>
    
	<div style="padding-top:0; padding-bottom:28px;">
		<ul data-role="listview">

			<ul data-role="listview" data-inset="true" align="center" style="margin:10px;">
				<li><img src="images/images.jpg" alt="Lights">Name: SOMETHING<br><br>ID: ##</li>
			</ul>
			
			<li style="text-align:center;margin-bottom:5px;">
				<select id="flip1" data-role="slider">
					<option value="off">Off</option>
					<option value="on">On</option>
				</select> 
			</li>
			
			<script>
			$(document).ready(function() {
				$( "#flip1" ).bind( "change", function(event, ui) {
					var flipVal = $('#flip1 option:selected').attr('value');
					if(flipVal == "on"){
						$('#level').val('99');
						$('#slider2').val('99');
						$('#slider2').slider('refresh');
						//execute function
					}
					else{
						$('#level').val('0');
						$('#slider2').val('0');
						$('#slider2').slider('refresh');
						//execute function
					}
				});
				
				$( "#slider2" ).bind( "change", function(event, ui) {
					$('#level').val($('#slider2').val());
				});

				$('a[role*="slider"]').touchend(function(event, ui) {
					//alert("Value = " + $(this).attr("aria-valuenow"));
					//execute function
				});
			});
			</script>
			
			<li>
				<input type="text" id="level" value="0" style="width:50px;text-align:center;margin: 5px auto 15px auto;" readonly>	
				<input type="range" name="slider2" id="slider2" value="0" min="0" max="99" />
			</li>
		</ul>
	</div>
    
	<div data-role="footer" style="POSITION: absolute; BOTTOM: 0px; WIDTH: 100%; TEXT-ALIGN: center;">
		<h4>BetaHomes &copy; 2012</h4>
	</div>
</div>
<!------------------------------------------------------------------------------------------>
<!--- Light 2 -->
<div id="nodeid2" class="all_divs">
	<div data-role="header" >
    	<!--<a data-rel="back" data-icon="arrow-l">Back</a>-->
		<a class="backMain" data-icon="arrow-l">Back</a>
		<h1>Entrance</h1>
        <a href="iLogout.php" rel="external" data-icon="delete" class="ui-btn-right">Logout</a>
	</div>
    
	<div data-role="content" style="padding-top:0; padding-bottom:28px;">
		<center>
			<br><br>
			<select name="flip-1" id="flip-1" data-role="slider">
				<option value="off">Off</option>
				<option value="on">On</option>
			</select> 
		</center>
	</div>

	<div data-role="footer" style="POSITION: absolute; BOTTOM: 0px; WIDTH: 100%; TEXT-ALIGN: center;">
		<h4>BetaHomes &copy; 2012</h4>
	</div>
</div>


<!------------------------------------------------------------------------------------------>

<style type="text/css">
#slider2 {display: none}
</style>

<script>
$(document).ready(function() {
	$('#liid1').click(function() {
		$('#main').hide();
		$('#nodeid1').show();
		$('#nodeid2').hide();
	});
});

$(document).ready(function() {
	$('#liid2').click(function() {
		$('#main').hide();
		$('#nodeid1').hide();
		$('#nodeid2').show();
	});
});

$(document).ready(function() {
	$('.backMain').click(function() {
		$('#main').show();
		$('#nodeid1').hide();
		$('#nodeid2').hide();
	});
});

$(document).ready(function() {
	$('#main').show();
	$('#nodeid1').hide();
	$('#nodeid2').hide();
});
</script>

</body>
</html>