
<?php require_once '/var/www/connection.php'; ?>
<?php

$data = new dataClass();

if(isset($_POST['action']) && !empty($_POST['action'])) {
    $action = $_POST['action'];
    switch($action) {

		case 'getPortNumber' : $data->getPortNumber(); break;
		case 'frameLoadLight' : $data->frameLoadLight(); break;
		case 'frameExecute' : $data->frameExecute(); break;
		case 'frameLoadSocket' : $data->frameLoadSocket(); break;

    }
}
//startPHP
//////////////////
class dataClass
{
	function frameLoadSocket()
	{
	
	$liids = "";
	$stack = array();
	$result = mysql_query("SELECT * FROM nodes");
	while($row = mysql_fetch_array($result)){
		$curr_db_id = (int)$row['node_id'];
		$curr_db_isON = $row['isON'];
		$curr_db_level = (int)$row['level'];
		$curr_db_name = $row['node_name'];
		
		
		//Check if name exist	
		$result_names = mysql_query("SELECT * FROM node_names WHERE node_id = '$curr_db_id'");
		$row_names = mysql_fetch_array($result_names);
		if($row_names['node_id']){
			$curr_db_name = $row_names['name'];
		}
		//end of check

		if($curr_db_id != 1){
			if(!$this->isDimmer($curr_db_id)){
				$liids = $liids . "<li><a id=\"liid{$curr_db_id}\">{$curr_db_name}</a></li>";
				array_push($stack, $curr_db_id);
			}
		}
	}
	
	
	$allData_1 = <<<DTA

<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet"  href="css/themes/default/jquery.mobile-1.1.1.css" />
<link rel="stylesheet" href="docs/_assets/css/jqm-docs.css" />
<script src="js/jquery.js"></script>
<script src="docs/_assets/js/jqm-docs.js"></script>
<script src="js/jquery.mobile-1.1.1.js"></script>
<!--<link href="styles/custom.css" rel="stylesheet" type="text/css">-->

<!------------------------------------------------------------------------------------------>
<!--- Main Outlets Page -->
<div id="main" class="all_divs">

	<div data-role="header">
    	<!--<a data-rel="back" data-icon="arrow-l">Back</a>-->
		<a href="main.php" rel="external" data-icon="arrow-l">Back</a>
		<h1>Outlets</h1>
        <a href="iLogout.php" rel="external" data-icon="delete" class="ui-btn-right">Logout</a>
	</div>

	<div id="mainContent">
		<img src="images/black-stripes-wallpaper-free-ipod-iphone.jpg" height="360" alt="Settings" style="width:100%;">
		<ul data-role="listview"  data-theme="a" data-inset="true" style="margin-left:10px;margin-right:10px; margin-top:-350px;">
			<!--<li><a id="liid1">Node Name 1</a></li>-->
			{$liids}
			<!-- LOAD more -->
		</ul>
	</div>
    
	<div data-role="footer" style="POSITION: absolute; BOTTOM: 0; WIDTH: 100%; TEXT-ALIGN: center; margin-bottom:-4px;">
		<h4>BetaHomes &copy; 2012</h4>
	</div>
</div>
DTA;

	
	$allData_2 = "";
	
	$result = mysql_query("SELECT * FROM nodes");
	while($row = mysql_fetch_array($result)){
		$curr_db_id = (int)$row['node_id'];
		$curr_db_isON = $row['isON'];
		$curr_db_level = (int)$row['level'];
		$curr_db_name = $row['node_name'];
		
		
		//Check if name exist	
		$result_names = mysql_query("SELECT * FROM node_names WHERE node_id = '$curr_db_id'");
		$row_names = mysql_fetch_array($result_names);
		if($row_names['node_id']){
			$curr_db_name = $row_names['name'];
		}
		//end of check

		if($curr_db_id != 1){
			if(!$this->isDimmer($curr_db_id)){
				
				
				
$temp_1 = <<<DTBA
<!------------------------------------------------------------------------------------------>
<div id="nodeid{$curr_db_id}">
 
	<div data-role="header" >
    	<a class="backMain" data-icon="arrow-l">Back</a>
		<h1>{$curr_db_name}</h1>
        <a href="iLogout.php" rel="external" data-icon="delete" class="ui-btn-right">Logout</a>
	</div>
    
	<div style="padding-top:0; padding-bottom:28px;">
		<ul data-role="listview">

			<ul data-role="listview" data-inset="true" align="center" style="margin:10px;">
				<li><img src="images/outlet.png" alt="Outlet">Name: {$curr_db_name}<br><br>ID: {$curr_db_id}</li>
			</ul>
			
			<li style="text-align:center;margin-bottom:5px;">
				<select id="flip{$curr_db_id}" data-role="slider">
DTBA;

if($curr_db_isON == "True"){
$temp_2 = <<<DTBB
					<option value="off">Off</option>
					<option selected="selected" value="on">On</option>
DTBB;
}
else{
$temp_2 = <<<DTBB
					<option selected="selected" value="off">Off</option>
					<option value="on">On</option>
DTBB;
}


$temp_3 = <<<DTBC
				</select> 
			</li>

			
			<script>
			$(document).ready(function() {
				$( "#flip{$curr_db_id}" ).bind( "change", function(event, ui) {
					var flipVal = $('#flip{$curr_db_id} option:selected').attr('value');
					if(flipVal == "on"){
						frameExecute('button_{$curr_db_id}_on');
					}
					else{
						frameExecute('button_{$curr_db_id}_off');
					}
				});

			});
			</script>
			
		</ul>
	</div>
    
	<div data-role="footer" style="POSITION: absolute; BOTTOM: 0px; WIDTH: 100%; TEXT-ALIGN: center; margin-bottom:-4px;">
		<h4>BetaHomes &copy; 2012</h4>
	</div>
</div>
<!------------------------------------------------------------------------------------------>
DTBC;
				$temp = $temp_1 . $temp_2 . $temp_3;
				
				$allData_2 = $allData_2 . $temp;
			}
		}
	}

	
//88888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888

	$allData_3 = "<script>";
	
	$socketsCount = count($stack);
	
	
	//--------------------------------------------
	//backMainClick
	$backMainClick = "
		$(document).ready(function() {
			$('.backMain').click(function() {
				$('#main').show();
	";
	for($i = 0; $i < $socketsCount; $i++){
		$backMainClick = $backMainClick . "$('#nodeid{$stack[$i]}').hide();";
	}
	$backMainClick = $backMainClick . "});});";
	//--------------------------------------------
	
	//--------------------------------------------
	//OnLoad
	$OnLoad = "
		$(document).ready(function() {
			$('#main').show();
	";
	for($i = 0; $i < $socketsCount; $i++){
		$OnLoad = $OnLoad . "$('#nodeid{$stack[$i]}').hide();";
	}
	$OnLoad = $OnLoad . "});";
	//--------------------------------------------
	
	//--------------------------------------------
	//OnEachClick
	$OnEachClick = "";
	

	for($i = 0; $i < $socketsCount; $i++){
		$currNodeID = $stack[$i];
		$OnEachClickB = "";
		
		$OnEachClickH = "
			$(document).ready(function() {
				$('#liid{$currNodeID}').click(function() {
					$('#main').hide();
					$('#nodeid{$currNodeID}').show();
		";
		
		for($j = 0; $j < $socketsCount; $j++){
			if($currNodeID != $stack[$i])
				$OnEachClickB = $OnEachClickB . "$('#nodeid{$stack[$i]}').hide();";
		}
		
		$OnEachClickT = "});});";
		
		$OnEachClick = $OnEachClick . $OnEachClickH . $OnEachClickB . $OnEachClickT;
	}
	
	
	//--------------------------------------------
	
	

$allData_3 = <<<DTC
<!------------------------------------------------------------------------------------------>

<script>

{$OnEachClick }

{$backMainClick}

{$OnLoad}

</script>

DTC;
	
	echo "$allData_1 $allData_2 $allData_3";
	
	}
	
//******************************************************************************************************************************************************
//******************************************************************************************************************************************************
//************************* STAR WALL ******************************************************************************************************************
//******************************************************************************************************************************************************
//******************************************************************************************************************************************************

	function frameExecute()
	{
		$command = $_POST['command'];
		//echo "$command\n";
		
		$commandArray = explode("_", $command); //[button | range], id, [on, off, <level>] ==> 3 elements
		$type = $commandArray[0];
		$id = $commandArray[1];
		$action = $commandArray[2];
		//echo "$type -- $id -- $action\n";
		
		$port = $this->getPortNumber("fcall");
		
		if($type == "button"){
			
			//button => switch			
			if($action == "off"){
				$action = "False";
			}
			else{
				$action = "True";
			}
			
			if($this->isDimmer($id)){
				if($action == "False"){
					shell_exec("perl /var/www/z-waver.pl '$port' dim '$id' '0'");
					mysql_query("UPDATE `_nodes_` SET `level`= '0' WHERE `node_id` = '$id'");
				}
				else{
					shell_exec("perl /var/www/z-waver.pl '$port' dim '$id' '99'");
					mysql_query("UPDATE `_nodes_` SET `level`= '99' WHERE `node_id` = '$id'");
				}
			}
			else{

				mysql_query("UPDATE `_nodes_` SET `isON`= '$action' WHERE `node_id` = '$id'");
				$action = ($action == "True") ? "on" : "off";
				shell_exec("perl /var/www/z-waver.pl '$port' switch '$id' '$action'");
				
			}
		}
		else{
			//echo "";
			//range => dim
			//shell_exec("perl /var/www/z-waver.pl '$port' dim '$id' '$action'");
			shell_exec("perl /var/www/z-waver.pl '$port' dim '$id' '$action'");
			mysql_query("UPDATE `_nodes_` SET `level`= '$action' WHERE `node_id` = '$id'");
		}
		
		echo "$type -- $id -- $action";
		
	}
	
	function isDimmer($node_id)
	{
		$result = mysql_query("SELECT * FROM command_classes WHERE node_id = '$node_id' AND id = '38'");
		$row = mysql_fetch_array($result);
		$id = (int)$row['id'];
		if($id == 38)
			return true;
		else
			return false;
	}
	
	//------------------------------------------------------
	function frameLoadLight()
	{
	
	$liids = "";
	
	$result = mysql_query("SELECT * FROM nodes");
	while($row = mysql_fetch_array($result)){
		$curr_db_id = (int)$row['node_id'];
		$curr_db_isON = $row['isON'];
		$curr_db_level = (int)$row['level'];
		$curr_db_name = $row['node_name'];
		
		
		//Check if name exist	
		$result_names = mysql_query("SELECT * FROM node_names WHERE node_id = '$curr_db_id'");
		$row_names = mysql_fetch_array($result_names);
		if($row_names['node_id']){
			$curr_db_name = $row_names['name'];
		}
		//end of check

		if($curr_db_id != 1){
			if($this->isDimmer($curr_db_id)){
				//$stage_2 = $stage_2 . "<script>$('#slider{$curr_db_id}').slider('disable');</script>";
				
				$liids = $liids . "<li><a id=\"liid{$curr_db_id}\">{$curr_db_name}</a></li>";
				
			}
		}
	}
	
	
	$allData_1 = <<<DTA

<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet"  href="css/themes/default/jquery.mobile-1.1.1.css" />
<link rel="stylesheet" href="docs/_assets/css/jqm-docs.css" />
<script src="js/jquery.js"></script>
<script src="docs/_assets/js/jqm-docs.js"></script>
<script src="js/jquery.mobile-1.1.1.js"></script>
<!--<link href="styles/custom.css" rel="stylesheet" type="text/css">-->

<!------------------------------------------------------------------------------------------>
<!--- Main Lights Page -->
<div id="main" class="all_divs">

	<div data-role="header">
    	<!--<a data-rel="back" data-icon="arrow-l">Back</a>-->
		<a href="main.php" rel="external" data-icon="arrow-l">Back</a>
		<h1>Lights</h1>
        <a href="iLogout.php" rel="external" data-icon="delete" class="ui-btn-right">Logout</a>
	</div>

	
	<!-- ORIGINAL
	<div id="mainContent">
		<ul data-role="listview" data-inset="true" style="margin:10px;">
			{$liids}
		</ul>
	</div>-->
	
	<!-- NEW -->
	<div id="mainContent">
		<img src="images/black-stripes-wallpaper-free-ipod-iphone.jpg" height="360" alt="Settings" style="width:100%;">
		<ul data-role="listview"  data-theme="a" data-inset="true" style="margin-left:10px;margin-right:10px; margin-top:-350px;">
			<!--<li><a id="liid1">Node Name 1</a></li>-->
			{$liids}
			<!-- LOAD more -->
		</ul>
	</div>
    
	<div data-role="footer" style="POSITION: absolute; float:bottom; BOTTOM: 0; WIDTH: 100%; TEXT-ALIGN: center; margin-bottom:-4px;">
		<h4>BetaHomes &copy; 2012</h4>
	</div>
</div>
DTA;

	
	$allData_2 = "";
	
	$result = mysql_query("SELECT * FROM nodes");
	while($row = mysql_fetch_array($result)){
		$curr_db_id = (int)$row['node_id'];
		$curr_db_isON = $row['isON'];
		$curr_db_level = (int)$row['level'];
		$curr_db_name = $row['node_name'];
		
		
		//Check if name exist	
		$result_names = mysql_query("SELECT * FROM node_names WHERE node_id = '$curr_db_id'");
		$row_names = mysql_fetch_array($result_names);
		if($row_names['node_id']){
			$curr_db_name = $row_names['name'];
		}
		//end of check

		if($curr_db_id != 1){
			if($this->isDimmer($curr_db_id)){
				
				
				
$temp_1 = <<<DTBA
<!------------------------------------------------------------------------------------------>
<div id="nodeid{$curr_db_id}">
 
	<div data-role="header" >
    	<a class="backMain" data-icon="arrow-l">Back</a>
		<h1>{$curr_db_name}</h1>
        <a href="iLogout.php" rel="external" data-icon="delete" class="ui-btn-right">Logout</a>
	</div>
    
	<div style="padding-top:0; padding-bottom:28px;">
		<ul data-role="listview">

			<ul data-role="listview" data-inset="true" align="center" style="margin:10px;">
				<li><img src="images/images.jpg" alt="Lights">Name: {$curr_db_name}<br><br>ID: {$curr_db_id}</li>
			</ul>
			
			<li style="text-align:center;margin-bottom:5px;">
				<select id="flip{$curr_db_id}" data-role="slider">
DTBA;

if($curr_db_level > 0){
$temp_2 = <<<DTBB
					<option value="off">Off</option>
					<option selected="selected" value="on">On</option>
DTBB;
}
else{
$temp_2 = <<<DTBB
					<option selected="selected" value="off">Off</option>
					<option value="on">On</option>
DTBB;
}


$temp_3 = <<<DTBC
				</select> 
			</li>

			<li>
				<input type="text" id="level{$curr_db_id}" value="{$curr_db_level}" style="width:50px;text-align:center;margin: 5px auto 15px auto;" readonly>	
				<input type="range" name="slider{$curr_db_id}" id="slider{$curr_db_id}" value="{$curr_db_level}" min="0" max="99" />
			</li>
			
			<script>
			$(document).ready(function() {
				$( "#flip{$curr_db_id}" ).bind( "change", function(event, ui) {
					var flipVal = $('#flip{$curr_db_id} option:selected').attr('value');
					if(flipVal == "on"){
						$('#level{$curr_db_id}').val('99');
						$('#slider{$curr_db_id}').val('99');
						$('#slider{$curr_db_id}').slider('refresh');
						frameExecute('button_{$curr_db_id}_on');
					}
					else{
						$('#level{$curr_db_id}').val('0');
						$('#slider{$curr_db_id}').val('0');
						$('#slider{$curr_db_id}').slider('refresh');
						frameExecute('button_{$curr_db_id}_off');
					}
				});
				
				$( "#slider{$curr_db_id}" ).bind( "change", function(event, ui) {
					$('#level{$curr_db_id}').val($('#slider{$curr_db_id}').val());
				});

				$('a[role*="slider"]').touchend(function(event, ui) {
					//alert("Value = " + $(this).attr("aria-valuenow"));
					var value = $(this).attr("aria-valuenow");
					frameExecute('range_{$curr_db_id}_' + value);
				});
			});
			</script>
			
		</ul>
	</div>
    
	<div data-role="footer" style="POSITION: absolute; BOTTOM: 0px; WIDTH: 100%; TEXT-ALIGN: center; margin-bottom:-4px;">
		<h4>BetaHomes &copy; 2012</h4>
	</div>
</div>
<!------------------------------------------------------------------------------------------>
DTBC;
				$temp = $temp_1 . $temp_2 . $temp_3;
				
				$allData_2 = $allData_2 . $temp;
			}
		}
	}

	
	$innerStyle = "";
	$stack = array();
	$result = mysql_query("SELECT * FROM nodes");
	while($row = mysql_fetch_array($result)){
		$curr_db_id = (int)$row['node_id'];
		$curr_db_isON = $row['isON'];
		$curr_db_level = (int)$row['level'];
		$curr_db_name = $row['node_name'];
		
		
		//Check if name exist	
		$result_names = mysql_query("SELECT * FROM node_names WHERE node_id = '$curr_db_id'");
		$row_names = mysql_fetch_array($result_names);
		if($row_names['node_id']){
			$curr_db_name = $row_names['name'];
		}
		//end of check

		if($curr_db_id != 1){
			if($this->isDimmer($curr_db_id)){
				$innerStyle = $innerStyle . "#slider{$curr_db_id} {display: none} ";
				array_push($stack, $curr_db_id);
			}
		}
	}

	$style = <<<STL
<style type="text/css">
	{$innerStyle}
</style>
STL;
//88888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888

	$allData_3 = "<script>";
	
	$lightsCount = count($stack);
	
	
	//--------------------------------------------
	//backMainClick
	$backMainClick = "
		$(document).ready(function() {
			$('.backMain').click(function() {
				$('#main').show();
	";
	for($i = 0; $i < $lightsCount; $i++){
		$backMainClick = $backMainClick . "$('#nodeid{$stack[$i]}').hide();";
	}
	$backMainClick = $backMainClick . "});});";
	//--------------------------------------------
	
	//--------------------------------------------
	//OnLoad
	$OnLoad = "
		$(document).ready(function() {
			$('#main').show();
	";
	for($i = 0; $i < $lightsCount; $i++){
		$OnLoad = $OnLoad . "$('#nodeid{$stack[$i]}').hide();";
	}
	$OnLoad = $OnLoad . "});";
	//--------------------------------------------
	
	//--------------------------------------------
	//OnEachClick
	$OnEachClick = "";
	

	for($i = 0; $i < $lightsCount; $i++){
		$currNodeID = $stack[$i];
		$OnEachClickB = "";
		
		$OnEachClickH = "
			$(document).ready(function() {
				$('#liid{$currNodeID}').click(function() {
					$('#main').hide();
					$('#nodeid{$currNodeID}').show();
		";
		
		for($j = 0; $j < $lightsCount; $j++){
			if($currNodeID != $stack[$i])
				$OnEachClickB = $OnEachClickB . "$('#nodeid{$stack[$i]}').hide();";
		}
		
		$OnEachClickT = "});});";
		
		$OnEachClick = $OnEachClick . $OnEachClickH . $OnEachClickB . $OnEachClickT;
	}
	
	
	//--------------------------------------------
	
	

$allData_3 = <<<DTC
<!------------------------------------------------------------------------------------------>

<script>

{$OnEachClick }

{$backMainClick}

{$OnLoad}

</script>

DTC;
	
	echo "$allData_1 $allData_2 $style $allData_3";
	
	}

	

	
	public function getPortNumber($call_type){

		//$call_type = "fcall" | browser call
		$output = shell_exec('ls -l /dev/ttyUSB*'); 
		
		//reverse string
		$reverse = strrev($output);

		//get number of USB port
		$usb_port = $reverse{1};

		if(is_numeric($usb_port))
			if ($call_type == "fcall")
				return "/dev/ttyUSB$usb_port";
			else
				echo "/dev/ttyUSB$usb_port";
		else 
		{
			//Sometimes for unknown reason EOL character is missing, 
			//therefore check if USB port is a first character
			$usb_port = $reverse{0};
			
			if(is_numeric($usb_port))
				if ($call_type == "fcall")
					return "/dev/ttyUSB$usb_port";
				else
					echo "/dev/ttyUSB$usb_port";
			else 
				if ($call_type == "fcall")
					return "Device is missing.";
				else
					echo "Device is missing.";
		}
	}


}

?>






















