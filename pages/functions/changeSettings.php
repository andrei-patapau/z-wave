<?php

require_once '/var/www/connection.php';

$data = new dataClass();

if(isset($_POST['action']) && !empty($_POST['action'])) {
    $action = $_POST['action'];
    switch($action) {
		case 'changeUsername' : $data->changeUsername(); break;
		case 'changePassword' : $data->changePassword(); break;
		
		case 'updateNodeName_' : $data->updateNodeName_(); break;
		case 'tableNamesLoad' : $data->tableNamesLoad(); break;
		case 'checkLoadNames' : $data->checkLoadNames(); break;
		
		case 'server_1_stop' : $data->server_1_stop(); break;
		case 'server_1' : $data->server_1(); break;
		case 'read_log' : $data->read_log(); break;
		case 'dbCheck' : $data->dbCheck(); break;
		case 'remove_file' : $data->remove_file(); break;
		case 'getXMLData' : $data->getXMLData(); break;
		case 'xmlHASH' : $data->xmlHASH(); break;
		case 'viewPort' : $data->viewPort(); break;
		
		case 'addNewUser' : $data->addNewUser(); break;
		case 'reloadUsers' : $data->reloadUsers(); break;
		case 'administratorOrNot' : $data->administratorOrNot(); break;
		case 'removeUser' : $data->removeUser(); break;
		
		//reloadUsers
    }
}
//administratorOrNot
//////////////////checkLoadNames
class dataClass
{	

	public function removeUser(){
	
		$user = $_POST['user'];
		
		$updateQuery = "DELETE FROM `user` WHERE `username` = '$user'";
		mysql_query($updateQuery);
		echo "User '$user' been successfully removed from database.";
	}

	public function administratorOrNot(){
	
		$administratorActive = $_POST['administratorActive'];
		
		$userID = $administratorActive[0];
		
		if($administratorActive[1] == "true")
			$active = 1;
		else
			$active = 0;
		

		$updateQuery = "UPDATE `user` SET `admin`= '$active' WHERE `username` = '$userID'";
		mysql_query($updateQuery);
	
	}

	public function reloadUsers(){
		$nonRootUsers = mysql_query("SELECT * FROM `user` WHERE root = '0'");
		$users = "";
		$foundAUser = 0;
		
		
		//ONCE
		while($nonRootUser = mysql_fetch_array($nonRootUsers)){
			$foundAUser = 1;
			
			$username = $nonRootUser['username'];
			$admin = $nonRootUser['admin'];
			
			if($admin){
				$admin = "Administrator";
				$checked = "checked";
			}
			else{
				$admin = "Not an Administrator";
				$checked = "";
			}
			
			
			$user = <<<CTN
<li class="ui-state-default" title="{$admin}" style="text-decoration: none;">
	<table width="100%">
		<tr>
			<td width="11%" align="center">
				<input type="checkbox" id="{$username}" style="margin-left:-7px;" onchange="administrator(this.id)" {$checked}/>
			</td>
			<td width="55%" align="center">
				'{$username}'
			</td>
			<td style="float:left;">
				<button name="{$username}" onclick="removeUser(this.name)">Remove User</button>
			</td>
		</tr>

	</table>
</li>
CTN;
			
			//<li>only at TIME on DATE</li>
			$users = $users . $user;
			
		}
		
		$header = "";
		if($foundAUser){
			$header = <<<HDR
<li class="ui-state-default" style="text-decoration: none;">
	<table width="100%">
		<tr>
			<td width="11%" style="font-size:70%;">
				Admin
			</td>
			<td width="55%" align="center" style="font-size:70%;">
				Username
			</td>
			<td style="float:right;">
				
			</td>
		</tr>
	</table>
</li>
HDR;
		}
		else{
			$header = <<<HDR
<li class="ui-state-default" style="text-decoration: none;">
	No users were found.
</li>
HDR;
		}
		
		$script = <<<LIB

<script type="text/javascript" src="../includes/jquery-1.7.2.js"></script>
<script>
$(document).ready(function() {
$("button").button();
});
</script>
LIB;
		
		echo "$header $users $script";
	}


	public function addNewUser(){
		
		//action: "addNewUser", uname: uname, pass: pass, pass_confirm: pass_confirm, admin: admin
		
		
		$tbl_name="user"; // Table name 

		// username and password sent from form 
		$uname = $_POST['uname']; 
		$pass = $_POST['pass'];
		$pass_confirm = $_POST['pass_confirm'];
		$admin = $_POST['admin'];
		

		//password length before sanitisation 
		$newPasswordLength = strlen($pass);

		// To protect MySQL injection (more detail about MySQL injection)
		$uname = stripslashes($uname);
		$pass = stripslashes($pass);
		$pass_confirm = stripslashes($pass_confirm);

		$uname = mysql_real_escape_string($uname);
		$pass = md5(mysql_real_escape_string($pass));
		$pass_confirm = md5(mysql_real_escape_string($pass_confirm));

		
		if($pass == $pass_confirm){
			//make sure password length is minimum 8 chars
			if($newPasswordLength >= 8){
			
				$sql="SELECT * FROM $tbl_name WHERE username = '$uname'";
				$result = mysql_query($sql);
				//Mysql_num_row is counting table row
				$count = mysql_num_rows($result);
				
				if(!$count){
					
					if($admin == "true")
						$admin = 1;
					else
						$admin = 0;
					
					$sql = "INSERT INTO `user`(`username`, `password`, `admin`) VALUES ('$uname', '$pass', '$admin');--";
					mysql_query($sql);
					
					if($admin)
						echo "<font color=\"green\">New user with administrative privileges has been added.</font>";
					else
						echo "<font color=\"green\">New user without administrative privileges has been added.</font>";
					
				}
				else{
					echo "Error: Username already exist. Try a different one.";
				}

			}
			else{
				echo "Error: Minimum password length is 8 characters";
			}
		}
		else{
			echo "New Password and Confirmation Password do NOT match";
		}

	}

	public function viewPort(){
		
		echo shell_exec('ls -l /dev/ttyUSB*');

	}

	public function xmlHASH(){
		
		$previous_md5 = "File is missing";
		
		if ($handle = opendir("/var/www/")) {
			while (false !== ($entry = readdir($handle))) {
				if ((strpos($entry, 'zwcfg_') !== false) && (strpos($entry, '.xml') !== false)){
					$previous_md5 = md5_file("/var/www/$entry");
				}
			}
			closedir($handle);
		}
		echo $previous_md5;
	}
	
	public function remove_file(){
	
		mysql_query("UPDATE `home` SET `xml_remove`= '1' WHERE `id` = '1'");
		
		$nodes = mysql_query("SELECT server_1_exit FROM home WHERE id = '1'");
		$server_1_exit = mysql_fetch_array($nodes);
		$server_1_exit = (int)$server_1_exit['server_1_exit']; //what db holds
		
		if($server_1_exit == 1){
			echo "File will be removed on Server resume.";
		}else{
			echo "File Removed";
		}
	}

	public function getXMLData(){
		
		//get XML file name
		$file_name = "file is missing";
		if ($handle = opendir("/var/www/")) {
			while (false !== ($entry = readdir($handle))) {
				if ((strpos($entry, 'zwcfg_') !== false) && (strpos($entry, '.xml') !== false)){
					$file_name = $entry;
				}
			}
			closedir($handle);
		}
		
		if($file_name != "file is missing"){
			//load XML
			$nodes = simplexml_load_file("/var/www/".$file_name);
			
			//output home id
			echo "Home ID: " . $nodes['home_id'] . "<br><br>";
			
			foreach ($nodes as $node){
				$id=$node['id'];
				$manufacturerID=$node->Manufacturer['id'];
				$productType=$node->Manufacturer->Product['type'];
				$productID=$node->Manufacturer->Product['id'];
				echo "Node ID: " . $id . " | " . "Manufacturer ID: " . $manufacturerID . " | " . "Product Type: " . $productType . " | " . "Product ID: " . $productID . "<br>";
				
				$commandClasses = $node->CommandClasses->CommandClass;
				
				foreach ($commandClasses as $commandClass){
					echo "------->";
					$cmID = $commandClass['id'];
					$cmName = $commandClass['name'];
					echo "CommandClass ID: " . $cmID . " |  Name: " . $cmName . "<br>";
				}
				
			}
		}
	}

	public function dbCheck(){
		
		//connect
		//$dbhost = 'localhost';
		//$dbuser = 'root';
		//$dbpass = 'root';
		//$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql');
		
		
		//select database
		mysql_select_db('currentState');
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

		$full = $full .  "<table class=\"databaseTable\" cellpadding=\"5\" border=\"1\" style=\"font-size:90%;\">";
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
		$full = $full .  "<br>";
		
		
		
		echo json_encode(array("full"=>$full,"mini"=>$mini));
		//execute the SQL query and return records
		//$result = mysql_query("SELECT onOff FROM nodes WHERE nodeID = 1");
		//$row = mysql_fetch_array($result);
		//echo $row{'onOff'};
		
	}

	public function read_log(){
		
		$log_exist = false;
		if ($handle = opendir("/var/www/")) {
			
			while (false !== ($entry = readdir($handle))) {
				
				//NOTE: is 'og_file.log' NOT 'log_file.log'
				//if I request 'log_file.log', then 'strpos' will return '0'(the first occurence of such string in file name)
				if (strpos($entry, 'log_file.log') !== false){
					$log_exist = true;
					
				}
			}
			closedir($handle);
		}
		
		if($log_exist){
			$myfile = 'log_file.log';
			$command = "tail -20 /var/www/$myfile";
			$lines = explode("\n", shell_exec($command));
			
			foreach($lines as $line){
				echo $line . "<br>";
			}
		}
		else{
			echo "+------------------------+<br>| Log File Does Not Exist |<br>| Please Start The Server |<br>+-------------------------+<br>";
		}

	}
	
	public function server_1(){
		
		
		$nodes = mysql_query("SELECT server_1_exit FROM home WHERE id = '1'");
		$server_1_exit = mysql_fetch_array($nodes);
		$server_1_exit = (int)$server_1_exit['server_1_exit']; //what db holds
		
		if($server_1_exit == 1){
			mysql_query("UPDATE `home` SET `server_1_exit`= '0' WHERE `id` = '1'");
			// Running in a background mode
			$Command = "php /var/www/py-openzwave/startServer.php";
			shell_exec("nohup $Command > /dev/null 2> /dev/null & echo $!");
		}
		else{
			mysql_query("UPDATE `home` SET `server_1_exit`= '0' WHERE `id` = '1'"); //just to ensure that flag is set to false
			echo "Server is already running.";
		}
	}
	
	public function server_1_stop(){
				
		mysql_query("UPDATE `home` SET `server_1_exit`= '1' WHERE `id` = '1'");
		
	}
	
	function checkLoadNames()
	{
		$md5 = "";
		$nodes = mysql_query("SELECT * FROM nodes");
		while($row = mysql_fetch_array($nodes)){
			$name = $row['node_name'];
			$md5 = $md5 . $name;
		}
		$md5 = md5($md5); //what we get currently
		
		$nodes = mysql_query("SELECT reloadNames FROM associations WHERE id = '1'");
		$row = mysql_fetch_array($nodes);
		$md5_db = $row['reloadNames']; //what db holds
		
		if($md5 != $md5_db){
			//update md5 in db AND return 1(reload frames)
			//echo '1';
			mysql_query("UPDATE `associations` SET `reloadNames`='$md5' WHERE id = '1'");
			echo "Nodes reload";
			
		}
		else{
			//return 0 - do not reload frames
			//echo '0';
			echo "Do Nothing";
		}
		
	}

	function tableNamesLoad()
	{
		$stage_1 = <<<ABC
		
<!DOCTYPE html>
<html>
<head>
<script type="text/javascript" src="/js/easy-editable-text.js"></script>
</head><body>
		<table id="renamingNodes" style="border-style:dotted;">
			<tr>
				<td>Node id</td>
				<td>Type</td>
				<td>Name</td>
			<tr>
ABC;
		$stage_2 = "";
		$stage_3 = "</table></body></html>";

		
		$result = mysql_query("SELECT * FROM nodes");
		
		while($row = mysql_fetch_array($result)){
			$curr_db_id = (int)$row['node_id'];
			$curr_db_name = $row['node_name'];
			
			$result_names = mysql_query("SELECT * FROM node_names WHERE node_id = '$curr_db_id'");
			$row_names = mysql_fetch_array($result_names);
			if($row_names['node_id']){
				$curr_db_name = $row_names['name'];
			}
			
			
			$type = "Type Not Found";
			$commandClasses = mysql_query("SELECT * FROM command_classes WHERE node_id = '$curr_db_id'");
			
			while($class = mysql_fetch_array($commandClasses)){
				if($class['id'] == 37){
					$type = "Socket";
					break;
				}else if($class['id'] == 38){
					$type = "Light";
					break;
				}else if($class['id'] == 33){
					$type = "Controller";
					break;
				}
			}
			
			//$stage_1 = $stage_1 . $curr_db_id . "-" . $type . "-" . $curr_db_name . "<br>";
			
			
			$stage_2 = $stage_2 . <<<ABCD
			<tr>
				<td>{$curr_db_id}</td>
				<td>{$type}</td>
				<td><label class="text_label">{$curr_db_name}</label><div class="edit"></div><input class="diff" id="node_{$curr_db_id}" type="text" value="{$curr_db_name}" /></td>
			<tr>
ABCD;
	
		}

		echo $stage_1 . $stage_2 . $stage_3;
	}

	function updateNodeName_()
	{	

		$id = $_POST['node_id'];
		$name = $_POST['node_name'];
		$order   = array(";", "--", "node_");
		$replace = '';
		
		$id = stripslashes($id);
		$id = mysql_real_escape_string($id);
		
		$name = stripslashes($name);
		$name = mysql_real_escape_string($name);
		
		//remove ';' and '--' characters
		$id = str_replace($order, $replace, $id);
		$name = str_replace($order, $replace, $name);
		

		//check if such node id exist
		$result_names = mysql_query("SELECT * FROM node_names WHERE node_id = '$id'");
		$row_names = mysql_fetch_array($result_names);
		if($row_names['node_id']){
			if(mysql_query("UPDATE `node_names` SET `name`='$name' WHERE node_id = '$id'"))
				echo "Name is updated";
			else
				echo "Update Name: Failed";
		}
		else{
			$addNodeQuery = "INSERT INTO `node_names`(`node_id`, `name`) VALUES ('$id', '$name')";
			mysql_query($addNodeQuery);
			echo "Name is updated";
		}
		

		
	}
	
//action: "changeUsername", username_u: uname, password_u: pass, newusername: nuname, confirmusername: cuname
	function changeUsername()
	{
		$tbl_name="user"; // Table name 

		// username and password sent from form 
		$oldusername=$_POST['username_u']; 
		$oldpassword=$_POST['password_u'];
		$newusername=$_POST['newusername'];
		$confirmusername=$_POST['confirmusername'];

		// To protect MySQL injection (more detail about MySQL injection)
		$oldusername = stripslashes($oldusername);
		$oldusername = mysql_real_escape_string($oldusername);
		
		$oldpassword = stripslashes($oldpassword);
		$oldpassword = mysql_real_escape_string($oldpassword);
		
		$newusername = stripslashes($newusername);
		$newusername = mysql_real_escape_string($newusername);
		
		$confirmusername = stripslashes($confirmusername);
		$confirmusername = mysql_real_escape_string($confirmusername);
		
		//***************************************************************
		$sql="SELECT * FROM user WHERE username='$oldusername'";
		$result=mysql_query($sql);
		
		$countCurrentUser = mysql_num_rows($result);
		
		if($countCurrentUser == 1){
			
			$currentUser = mysql_fetch_array($result);
			$salt = $currentUser['salt'];
			
			//$saltedMD5Pass = md5($oldpassword . $salt);
			$saltedSHA256Pass = hash('sha256', $oldpassword . $salt);
			//***************************************************************
			
			if($newusername == $confirmusername){
				$sql="SELECT * FROM $tbl_name WHERE username='$oldusername' and password='$saltedSHA256Pass'";
				$result=mysql_query($sql);

				// Mysql_num_row is counting table row
				$count=mysql_num_rows($result);

				// If result matched $oldusername and $saltedSHA256Pass, table row must be 1 row
				if($count == 1){
				
					$sql = "SELECT * FROM $tbl_name WHERE username = '$newusername'";
					$result = mysql_query($sql);
					$count = mysql_num_rows($result);
					
					if(!$count){
						// Register $oldusername, $saltedSHA256Pass and redirect to file "main.php"
						$sql = "UPDATE $tbl_name SET username='$newusername' WHERE username='$oldusername' and password='$saltedSHA256Pass'";
						mysql_query($sql);

						session_start();
						$_SESSION["myusername"] = $newusername;
						$_SESSION["mypassword"] = $saltedSHA256Pass;
						
						//header("Location: main.php");
						echo "<font color=\"green\">Username Changed to '$newusername'</font>";
					}
					else{
						echo "'$newusername' already exist. Try different username.";
					}
				

				}
				else {
					echo "Incorrect Password";
				}
			}
			else{
				echo "New Username and Confirmation Username do NOT match";
			}
		}
		else{
			echo "Incorrect Current Username";
		}

		
	}

	function changePassword()
	{
		
		// username and password sent from form 
		$oldusername=$_POST['oldusername']; 
		$oldpassword=$_POST['oldpassword'];
		$newpassword=$_POST['newpassword'];
		$confirmpassword=$_POST['confirmpassword'];
		
		//password length before sanitisation 
		$newPasswordLength = strlen($newpassword);

		// To protect MySQL injection (more detail about MySQL injection)
		$oldusername = stripslashes($oldusername);
		$oldusername = mysql_real_escape_string($oldusername);
		
		$oldpassword = stripslashes($oldpassword);
		$oldpassword = mysql_real_escape_string($oldpassword);
		//--------------------------------------------------------
		$sql="SELECT * FROM user WHERE username='$oldusername'";
		$result=mysql_query($sql);
		
		$currentUser = mysql_fetch_array($result);
		$salt = $currentUser['salt'];
		
		//$saltedOldMD5Pass = md5($oldpassword . $salt);
		$oldSaltedSHA256Pass = hash('sha256', $oldpassword . $salt);
		
		$sql="SELECT * FROM user WHERE username='$oldusername' and password='$oldSaltedSHA256Pass'";
		$result=mysql_query($sql);

		// Mysql_num_row is counting table row
		$count=mysql_num_rows($result);
		//--------------------------------------------------------
		
		//check if username / password combination exist
		if($count==1){

			$newpassword = stripslashes($newpassword);
			$newpassword = mysql_real_escape_string($newpassword);
			
			$confirmpassword = stripslashes($confirmpassword);
			$confirmpassword = mysql_real_escape_string($confirmpassword);
			
			if($newpassword == $confirmpassword){
				//make sure password length is minimum 8 chars
				if($newPasswordLength >= 8){
					
					//$newSaltedMD5Pass = md5($newpassword . $salt);
					$newSaltedSHA256Pass = hash('sha256', $newpassword . $salt);
					$sql="UPDATE `user` SET `password`='$newSaltedSHA256Pass' WHERE username='$oldusername' and password='$oldSaltedSHA256Pass'";
					$result=mysql_query($sql);
			
					session_start();
					$_SESSION["myusername"] = $oldusername;
					$_SESSION["mypassword"] = $newSaltedSHA256Pass;
					
					//header("Location: main.php");
					echo "<font color=\"green\">Password Changed</font>";

				}
				else{
					echo "Error: Minimum password length is 8 characters";
				}
			}
			else{
				echo "New Password and Confirmation Password do NOT match";
			}
			
		}
		else{
			echo "Wrong Username or Password";
		}

		
	}
}



?>