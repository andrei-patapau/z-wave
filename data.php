<?php require_once 'execute.php'; ?>
<?php require_once 'connection.php'; ?>
<?php

define("HTTP_HOST", "192.168.1.102");
define("REMOTE_PORT", 2222);

$data = new dataClass();

if(isset($_POST['action']) && !empty($_POST['action'])) {
    $action = $_POST['action'];
    switch($action) {
		case 'execPerlOFF' : $data->execPerlOFF(); break;
		case 'execPerlON' : $data->execPerlON(); break;
		
		case 'getPortNumber' : $data->getPortNumber(); break;
		case 'viewPort' : $data->viewPort(); break;
		
		case 'remove_file' : $data->remove_file(); break;
		case 'getXMLData' : $data->getXMLData(); break;
		case 'xmlHASH' : $data->xmlHASH(); break;
		case 'dbCheck' : $data->dbCheck(); break;
		
		//------------------------------------------------------
		
		
		
		
		case 'frameLoad' : $data->frameLoad(); break;
		case 'frameExecute' : $data->frameExecute(); break;
		case 'frameCheck' : $data->frameCheck(); break;
		case 'frameReload' : $data->frameReload(); break;
		
		
		
    }
}
//startPHP
//////////////////
class dataClass
{	



	function frameReload()
	{
		$random = rand(0, 999999);
		while(true){
			if(mysql_query("UPDATE `home` SET `nodes_md5_db`='$random' WHERE id = 1"))
				break;
		}
	}
	
	function frameCheck()
	{
		sleep(2);
		$md5 = "";
		$nodes = mysql_query("SELECT * FROM nodes");
		while($row = mysql_fetch_array($nodes)){
			$isON = $row['isON'];
			$level = $row['level'];
			$md5 = $md5 . $isON . $level;
		}
		$md5 = md5($md5); //what we get currently
		
		$nodes = mysql_query("SELECT nodes_md5_db FROM home WHERE id = 1");
		$row = mysql_fetch_array($nodes);
		$md5_db = $row['nodes_md5_db']; //what db holds
		
		if($md5 != $md5_db){
			//update md5 in db AND return 1(reload frames)
			//echo '1';
			mysql_query("UPDATE `home` SET `nodes_md5_db`='$md5' WHERE id = 1");
			error_log($md5."-".$md5_db.'-true'.PHP_EOL, 3, '/var/www/log_file.log');
			echo 'true';
			
		}
		else{
			//return 0 - do not reload frames
			//echo '0';
			error_log($md5."-".$md5_db.'-false'.PHP_EOL, 3, '/var/www/log_file.log');
			echo 'false';
		}
		
	}
	
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
					shell_exec("perl z-waver.pl '$port' dim '$id' '0'");
					mysql_query("UPDATE `_nodes_` SET `level`= '0' WHERE `node_id` = '$id'");
				}
				else{
					shell_exec("perl z-waver.pl '$port' dim '$id' '99'");
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
			//shell_exec("perl z-waver.pl '$port' dim '$id' '$action'");
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
	function frameLoad()
	{
		$stage_2 = "";
		$stage_3 = "</head><body>";
		$stage_4 = "";
		$stage_5 = "</body></html>";
		
		$ALL_NODES = "";
		///////////////////////////////////////////STAGE_1//////////////////////////////////////////////////////////////
		$stage_1 = "
<!DOCTYPE html>
<html>
<head>
<link href=\"js/jquery-ui.css\" rel=\"stylesheet\" type=\"text/css\"/>
<script src=\"js/jquery-ui.min.js\"></script>
<script>
$(document).ready(function() {
$(\"button\").button();
});
</script>";
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		

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
						
				//*************************************************************************************************************************************************
				/*
				$stage_2 = $stage_2 . "<script type=\"text/javascript\">";
				$stage_2 = $stage_2 . "$(function() {";
				$stage_2 = $stage_2 . "$( \"#slider" . "$curr_db_id" . "\" ).slider({";
				$stage_2 = $stage_2 . "value:" . "$curr_db_level" . ",";
				$stage_2 = $stage_2 . "min: 0,";
				$stage_2 = $stage_2 . "max: 99,";
				$stage_2 = $stage_2 . "step: 1,";
				
				$stage_2 = $stage_2 . "stop: function( event, ui ) {";
				$stage_2 = $stage_2 . "showValue_" . "$curr_db_id" . "_(ui.value);";
				$stage_2 = $stage_2 . "},";
				
				$stage_2 = $stage_2 . "create: function( event, ui ) {";
				$stage_2 = $stage_2 . "document.getElementById(\"range_" . "$curr_db_id" . "\").innerHTML=" . "$curr_db_level" . ";";
				$stage_2 = $stage_2 . "}";
				
				$stage_2 = $stage_2 . "});";
				//$stage_2 = $stage_2 . "showValue_" . "$curr_db_id" . "_(ui.value);";
				$stage_2 = $stage_2 . "});";

				$stage_2 = $stage_2 . "function showValue_" . "$curr_db_id" . "_(newValue){document.getElementById(\"range_" . "$curr_db_id" . "\").innerHTML=newValue;var a = \"range_" . "$curr_db_id" . "_\";var c = a.concat(newValue);frameExecute(c);}";
				$stage_2 = $stage_2 . "function clickButton_" . "$curr_db_id" . "_(id){frameExecute(id);}</script>";
				*/
				//*************************************************************************************************************************************************
				//Let's try this
				$stage_2_1 = <<<STG
<script type="text/javascript">
$(function() {
$( "#slider{$curr_db_id}" ).slider({
value:{$curr_db_level},
min: 0,
max: 99,
step: 1,

slide: function( event, ui ) {
//$('#range_{$curr_db_id}').val(ui.value);
document.getElementById("range_{$curr_db_id}").innerHTML=ui.value;
},

stop: function( event, ui ) {
showValue_{$curr_db_id}_(ui.value);
},

create: function( event, ui ) {
document.getElementById("range_{$curr_db_id}").innerHTML={$curr_db_level};
}

});
});

function showValue_{$curr_db_id}_(newValue){
document.getElementById("range_{$curr_db_id}").innerHTML=newValue;
var a = "range_{$curr_db_id}_";
var c = a.concat(newValue);
frameExecute(c);
}

function clickButton_{$curr_db_id}_(id){frameExecute(id);}
</script>
STG;
				
				
				$stage_2 = $stage_2 . $stage_2_1;
				//*************************************************************************************************************************************************
				if(!$this->isDimmer($curr_db_id)){
					$stage_2 = $stage_2 . "<script>$('#slider{$curr_db_id}').slider('disable');</script>";
				}
				
				/*
				if(!$this->isDimmer($curr_db_id)){
				$stage_2 = $stage_2 . "
				<script>
				$(document).ready(function() {
				//$('#range_{$curr_db_id}').hide();
				//$('#slider{$curr_db_id}').hide();
				$('#range_{$curr_db_id}').attr("disabled", true);
				$('#slider{$curr_db_id}').attr("disabled", true);
				});
				</script>";
				}
				*/

				//*************************************************************************************************************************************************
				//*************************************************************************************************************************************************
$stage_4_1 = <<<STA

<fieldset class="ui-corner-all" style="width:195px; background-color:#FAFAFA; display:inline; text-align:center; margin:0 20px;">
	<legend>{$curr_db_name} [{$curr_db_id}]</legend>
	<table width="100%" height="165px">
		<!-- row 1 -->
		<tr>
			<td>
				<table width="100%">
					<tr>
						<td width="50%">
							<table width="100%" style="margin-left:5px; margin-right:45px; margin-top:10px;">
								<tr>
									<td>
										<button id="button_{$curr_db_id}_on" onClick="clickButton_{$curr_db_id}_(this.id)">ON</button>
									</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td>
										<button id="button_{$curr_db_id}_off" onClick="clickButton_{$curr_db_id}_(this.id)">OFF</button>
									</td>
								</tr>
							</table>
						</td>
						<td width="50%">
STA;

$stage_4 = $stage_4 . $stage_4_1;

if($this->isDimmer($curr_db_id)){
	if($curr_db_level > 0){
		
		$stage_4 = $stage_4 . "<img src=\"js/lightBulb.png\" style=\"margin-bottom:10px;\" width=\"76\" height=\"86\">";
	}
	else{
		
		$stage_4 = $stage_4 . "<img src=\"js/lightBulbGrey.png\" style=\"margin-bottom:10px;\" width=\"76\" height=\"86\">";
	}
}
else{
	if($curr_db_isON == "True"){
		
		$stage_4 = $stage_4 . "<img src=\"js/outlet_ON.png\" width=\"55\" height=\"86\">";
	}
	else{
		
		$stage_4 = $stage_4 . "<img src=\"js/outlet_OFF.png\" width=\"55\" height=\"86\">";
	}
}

$stage_4_2 = <<<STB
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<!-- row 2 -->
		<tr>
			<td height="40px">
				<div id="slider{$curr_db_id}" style="font-size:50%;margin:10px 15px 5px 15px;" ></div>
				<div style="margin-bottom:5px;"><span id="range_{$curr_db_id}" style="border:0; color:#606060; font-weight:bold;">0</span></div>
			</td>
		</tr>
	</table>
</fieldset>

STB;

$stage_4 = $stage_4 . $stage_4_2;

				//*************************************************************************************************************************************************
				
				
			}
		}
		
		$ALL_NODES = $stage_1 . $stage_2 . $stage_3 . $stage_4 . $stage_5;
		
		
		echo $ALL_NODES;
		
		//echo "<br>Data is here<br><br>";
	}
	//------------------------------------------------------
	function run_in_background($Command, $Priority = 0)
	{
		if($Priority)
			$PID = shell_exec("nohup nice -n $Priority $Command 2> /dev/null & echo $!");
		else
			$PID = shell_exec("nohup $Command > /dev/null 2> /dev/null & echo $!"); //$PID = shell_exec("nohup $Command 2> /dev/null & echo $!");
		
		return($PID);
	}

	function is_process_running($PID)
	{
		exec("ps $PID", $ProcessState);
		return(count($ProcessState) >= 2);
	}
	//------------------------------------------------------
	

	
	public function server_1_DO_NOT_USE_(){
		
		$port = $this->getPortNumber("fcall");
		
		$str = shell_exec('python ' . 'py-openzwave/examples/testServerFile.py ' . $port);
		
		$order   = array("[", "'", "]", ",");
		$replace = '';
		$str = str_replace($order, $replace, $str);
		
		$nodeArray = explode(" ", $str); //[node_id, isOn, level]...
		// nodeArray contains node_id, isOn and level sequences for all nodes
		$nodeArrayLength = count($nodeArray); //returns number of elements in nodeArray
		
		///PUT IT ALL IN DATABASE
		//connect first
		//$dbhost = 'localhost';
		//$dbuser = 'root';
		//$dbpass = 'root';
		//$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql');

		
		$found = false;
		for($i = 0; $i < $nodeArrayLength; $i = $i + 3){
			$i1 = $i;
			$i2 = $i + 1;
			$i3 = $i + 2;
			
			//a bit inefficient approach, but it works!
			$result = mysql_query("SELECT * FROM nodes");
			while($row = mysql_fetch_array($result)){
				if($row['node_id'] == $nodeArray[$i]){
					$found = true; //node exist
					$tempNodeID = $row['node_id'];
					$tempNodeIsON = $row['isON'];
					$tempNodeLevel = $row['level'];
				}
			}
			
			if($found){
				//check here if isOn or level been changed
				if($nodeArray[$i2] == "True")
					$isON = 1;
				else
					$isON = 0;
				
				if($isON != $tempNodeIsON){
					//Update node
					$updateQuery = "UPDATE `nodes` SET `isON`= $nodeArray[$i2] WHERE `node_id` = $tempNodeID";
					mysql_query($updateQuery);
				}
				//=================================
				if($nodeArray[$i3] != $tempNodeLevel){
					//Update node
					$updateQuery = "UPDATE `nodes` SET `level`= $nodeArray[$i3] WHERE `node_id` = $tempNodeID";
					mysql_query($updateQuery);
				}
				
				echo $tempNodeID . "," . $tempNodeIsON . "," . $isON . "," . $tempNodeLevel . " | ";
			}
			else{
				//add new node to DB
				$addNodeQuery = "INSERT INTO `nodes`(`node_id`, `isON`, `level`) VALUES ($nodeArray[$i1], $nodeArray[$i2], $nodeArray[$i3])";
				mysql_query($addNodeQuery);
			}
			
			$found = false;
			//echo $nodeArray[$i] . $nodeArray[$i+1] . $nodeArray[$i+2];
		}
		
	}

	public function xmlHASH(){
		
		$previous_md5 = "File is missing";
		
		if ($handle = opendir(getcwd())) {
			while (false !== ($entry = readdir($handle))) {
				if ((strpos($entry, 'zwcfg_') !== false) && (strpos($entry, '.xml') !== false)){
					$previous_md5 = md5_file("$entry");
				}
			}
			closedir($handle);
		}
		echo $previous_md5;
	}

	public function dbCheck(){
		
		//connect
		//$dbhost = 'localhost';
		//$dbuser = 'root';
		//$dbpass = 'root';
		//$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql');
		
		//======================================
		
		//a bit inefficient approach, but it works!
		$result = mysql_query("SELECT * FROM home");
		$row = mysql_fetch_array($result);
		
		$full = "";
		$mini = "";
		$full = $full . "<br>";
		$full = $full .  "Home ID: " . $row['home_id'];
		$full = $full .  "<br>";
		$full = $full .  "<br>";
		$full = $full .  "<font size=\"4\">";
		$full = $full .  "<table cellpadding=\"5\" border=\"1\">";
		$full = $full .  "<tr>";
		$full = $full .  "<td> node_id </td>";
		$full = $full .  "<td> manufacturer_id </td>";
		$full = $full .  "<td> product_type_id </td>";
		$full = $full .  "<td> product_id </td>";
		$full = $full .  "<td> manifacturer_name </td>";
		$full = $full .  "<td> product_name </td>";
		$full = $full .  "<td> isON </td>";
		$full = $full .  "<td> level </td>";
		$full = $full .  "</tr>";
		
		
		$mini = <<<STD
<table style="border:1px dotted black;">
<tr><td style="padding:2px;border:1px dotted black;">ID</td><td style="padding:2px;border:1px dotted black;">isON</td><td style="padding:2px;border:1px dotted black;">level</td></tr>
STD;
		
		
		$result = mysql_query("SELECT * FROM nodes");
		while($row = mysql_fetch_array($result)){
			$full = $full .  "<tr>";
			$full = $full .  "<td>" . $row['node_id'] . "</td>";
			$full = $full .  "<td>" . $row['manufacturer_id'] . "</td>";
			$full = $full .  "<td>" . $row['product_type_id'] . "</td>";
			$full = $full .  "<td>" . $row['product_id'] . "</td>";
			$full = $full .  "<td>" . $row['manifacturer_name'] . "</td>";
			$full = $full .  "<td>" . $row['product_name'] . "</td>";
			$full = $full .  "<td>" . $row['isON'] . "</td>";
			$full = $full .  "<td>" .$row['level'] . "</td>";
			//echo "Node ID: " . $tempNodeID . "  |  Node IsOn: " . $tempNodeIsON . "  |  Node Level: " . $tempNodeLevel . "<br><br>";
			$full = $full .  "</tr>";
			
			$mini_t = <<<CPB
<tr><td style="padding:2px;border:1px dotted black;">{$row['node_id']}</td><td style="padding:2px;border:1px dotted black;">{$row['isON']}</td><td style="padding:2px;border:1px dotted black;">{$row['level']}</td></tr>
CPB;
			$mini = $mini . $mini_t;
			
		}
		
		$mini = $mini . "</table>";
		
		$full = $full .  "</table>";
		$full = $full .  "</font>";
		$full = $full .  "<br>";
		
		
		
		echo json_encode(array("full"=>$full,"mini"=>$mini));
		//execute the SQL query and return records
		//$result = mysql_query("SELECT onOff FROM nodes WHERE nodeID = 1");
		//$row = mysql_fetch_array($result);
		//echo $row{'onOff'};
		
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

	public function execPerlOFF(){
	
		$port = $this->getPortNumber("fcall");
		$shell = new PHPsh;
		ob_clean();//Clean (erase) the output buffer (does not destroy the output buffer)
		ob_start();//Turn on output buffering
		$shell->execCommand("./z-waver.pl '$port' switch 3 off", $OutputEscapeFlag);
		$value = ob_get_contents();//Return the contents of the output buffer
		ob_end_clean();//Clean (erase) the output buffer and turn off output buffering
		
		$status = "OFF";
		echo json_encode(array("output"=>$value,"status"=>$status));

	}

	public function execPerlON(){
		$port = $this->getPortNumber("fcall");
		
		$shell = new PHPsh;
		ob_clean();//Clean (erase) the output buffer (does not destroy the output buffer)
		ob_start();//Turn on output buffering
		$shell->execCommand("./z-waver.pl '$port' switch 3 on", $OutputEscapeFlag);
		$value = ob_get_contents();//Return the contents of the output buffer
		ob_end_clean();//Clean (erase) the output buffer and turn off output buffering

		$status = "ON";
		echo json_encode(array("output"=>$value,"status"=>$status));

	}
	
	public function viewPort(){
		
		echo shell_exec('ls -l /dev/ttyUSB*');

	}

	public function getXMLData(){
		
		//get XML file name
		$file_name = "file is missing";
		if ($handle = opendir(getcwd())) {
			while (false !== ($entry = readdir($handle))) {
				if ((strpos($entry, 'zwcfg_') !== false) && (strpos($entry, '.xml') !== false)){
					$file_name = $entry;
				}
			}
			closedir($handle);
		}
		
		//load XML
		$nodes = simplexml_load_file($file_name);
		
		//output home id
		echo "Home ID: " . $nodes['home_id'] . "<br><br>";
		
		foreach ($nodes as $node){
			$id=$node['id'];
			$manufacturerID=$node->Manufacturer['id'];
			$productType=$node->Manufacturer->Product['type'];
			$productID=$node->Manufacturer->Product['id'];
			echo "Node ID: " . $id . " | " . "Manufacturer ID: " . $manufacturerID . " | " . "Product Type: " . $productType . " | " . "Product ID: " . $productID . "<br>";
			
			$commandClasses = $node->CommandClasses->CommandClass;
			echo "<UL>";
			foreach ($commandClasses as $commandClass){
				
				$cmID = $commandClass['id'];
				$cmName = $commandClass['name'];
				echo "CommandClass ID: " . $cmID . " |  Name: " . $cmName . "<br>";
			}
			echo "</UL>";
		}
	}
	
	public function remove_file(){
	
		if ($handle = opendir(getcwd())) {
		
			while (false !== ($entry = readdir($handle))) {
				if ((strpos($entry, 'zwcfg_') !== false) && (strpos($entry, '.xml') !== false)){
					
					//Before removing file need to get it's data
					unlink ("$entry");
					echo "File Removed - $entry"; 
				}
			}
			
			closedir($handle);
		}
	}
}

?>






















