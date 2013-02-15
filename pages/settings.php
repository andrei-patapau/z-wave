<?php
session_start();

if(!isset($_SESSION["myusername"])){
header("location:/Logout.php");//clean session and return to index.php
}
if(!$_SESSION["admin"]){
echo "<script>alert(\"Sorry, but only a user with administrative privileges may access this page.\");window.location = \"/main.php\"</script>";
}
require_once '/var/www/connection.php';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../includes/imports.php"); ?>
<link href="functions/settings.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="functions/settings.js"></script>

<style>
#tabs { margin: 1em 0 0 0; border-bottom: .1em solid #000000;}
#tabs li { display: inline; width: 79px; padding: 2px 8px 2px 8px; border: 1px solid #4a7194; margin-left: .5em; text-decoration: none; cursor: pointer; background:#efefef;}
#tabs li:hover {background:#bbbbbb;}
.databaseTable td {padding:0 3px;}
#users_menu li {text-decoration: none; text-align:left; font-size:13px; font-weight: bold; display:block; padding:5px 5px 5px 15px; background-color:#5B5B66; color: #000000; margin:10px 0 10px 0; }
</style>
<script>


//hide unused buttons

$(document).ready(function() {
	$('#tab_settings').click(function() {
	
	  $('#system_settings').show();
	  $('#user_settings').hide();
	  $('#log_settings').hide();
	  $('#xml_settings').hide();
	  
	  $('#tab_settings').css('font-weight', 'bold');
	  $('#tab_user').css('font-weight', 'normal');
	  $('#tab_log').css('font-weight', 'normal');
	  $('#tab_xml').css('font-weight', 'normal');

	});
});

$(document).ready(function() {
	$('#tab_user').click(function() {
	
	  $('#user_settings').show();
	  $('#system_settings').hide();
	  $('#log_settings').hide();
	  $('#xml_settings').hide();
	  
	  $('#tab_user').css('font-weight', 'bold');
	  $('#tab_settings').css('font-weight', 'normal');
	  $('#tab_log').css('font-weight', 'normal');
	  $('#tab_xml').css('font-weight', 'normal');

	});
});

$(document).ready(function() {
	$('#tab_log').click(function() {
	
	  $('#user_settings').hide();
	  $('#system_settings').hide();
	  $('#log_settings').show();
	  $('#xml_settings').hide();
	  
	  $('#tab_user').css('font-weight', 'normal');
	  $('#tab_settings').css('font-weight', 'normal');
	  $('#tab_log').css('font-weight', 'bold');
	  $('#tab_xml').css('font-weight', 'normal');

	});
});

$(document).ready(function() {
	$('#tab_xml').click(function() {
	
	  $('#user_settings').hide();
	  $('#system_settings').hide();
	  $('#log_settings').hide();
	  $('#xml_settings').show();
	  
	  $('#tab_user').css('font-weight', 'normal');
	  $('#tab_settings').css('font-weight', 'normal');
	  $('#tab_log').css('font-weight', 'normal');
	  $('#tab_xml').css('font-weight', 'bold');

	});
});

$(document).ready(function() {
	$('#user_settings').show();
	$('#system_settings').hide();
	$('#log_settings').hide();
	$('#xml_settings').hide();
	$('#tab_user').css('font-weight', 'bold');

});

$(document).ready(function() {
	reloadUsers(); 
});

</script>



<title>BetaHomes</title>
</head>
<body>
<div id="wrapper">
	<div class="container" style="border-style:groove;">
		<?php include("../includes/header.php"); ?>
		<center>
		<ul id="tabs">
		  <li id="tab_user">User</li>
		  <li id="tab_settings" >Nodes</li>
		  <li id="tab_log" >Log, Start/Stop Server</li>
		  <li id="tab_xml" >XML</li>
		</ul>
			<!-- START BODY OF CONTAINER WITHIN CENTER TAG -->
			<div id="divBorder">
				<div id="user_settings">
					<table>
					<tr>
						<td>
							<fieldset class="ui-corner-all" style="width:400px; display:inline; min-height:300px; text-align:center; ">

								<legend align="center"><h2 style="color: #439deb;">Change Username</h2></legend>
								
								<table align="center" width="100%" style="border-style:none;margin-top:10px;">
									<tr>
										<td>
											<table align="center" width="100%" style="color: #000000;text-align:center;">
												<tr>
													<td width="55%"><b>Username</b></td>
													<td width="6px"><b>:</b></td>
													<td width="140px"><input id="username_u" type="text" value="<?php $u = $_SESSION["myusername"]; echo "$u"; ?>"></td>
												</tr>
												<tr>
													<td><b>Password</b></td>
													<td><b>:</b></td>
													<td><input id="password_u" type="password"></td>
												</tr>
												<tr>
													<td><b>New Username</b></td>
													<td><b>:</b></td>
													<td><input id="newusername" type="text"></td>
												</tr>
												<tr>
													<td><b>Confirm Username</b></td>
													<td><b>:</b></td>
													<td><input id="confirmusername" type="text"></td>
												</tr>
												<tr>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
												</tr>

											</table>
										</td>
									</tr>
									<tr>
										<td align="center"><button onclick="changeUsername()"><b style="color:#000000">Change Username</b></button></td>
									</tr>
								</table>
								<br>
								<div id="statusUsername" style="color:red;"></div>
							</fieldset>
						</td>
						<td>&nbsp;&nbsp;&nbsp;</td>
						<td>
							<fieldset class="ui-corner-all" style="width:400px; display:inline; min-height:300px; text-align:center">
								<legend align="center"><h2 style="color: #439deb;">Change Password</h2></legend>
								<table align="center" width="100%" style="border-style:none;margin-top:10px;">
									<tr>
										<td>
											<table width="100%" style="color: #000000;">
												<tr>
													<td width="55%"><b>Username</b></td>
													<td width="6px"><b>:</b></td>
													<td width="140px"><input id="oldusername" type="text" value="<?php $u = $_SESSION["myusername"]; echo "$u"; ?>"></td>
												</tr>
												<tr>
													<td><b>Password</b></td>
													<td><b>:</b></td>
													<td><input id="oldpassword" type="password"></td>
												</tr>
												<tr>
													<td><b>New Password</b></td>
													<td><b>:</b></td>
													<td><input id="newpassword" type="password"></td>
												</tr>
												<tr>
													<td><b>Confirm Password</b></td>
													<td><b>:</b></td>
													<td><input id="confirmpassword" type="password"></td>
												</tr>
												<tr>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
												</tr>

											</table>
										</td>
									</tr>
									<tr>
										<td align="center"><button onclick="changePassword()"><b style="color:#000000">Change Password</b></button></td>
									</tr>
								</table>
								<br>
								<div id="statusPassword" style="color:red"></div>
							</fieldset>
						</td>
					</tr>
					<!------------------------------------------------------->
					<?php
					$test = 1;
					
					$rootSettings = <<<RTD
					<tr>
						<td>
							<fieldset class="ui-corner-all" style="width:400px; display:inline; min-height:250px; text-align:center; margin-top:15px;">
								<legend align="center"><h2 style="color: #439deb;">Add New User</h2></legend>
								<table align="center" width="100%" style="border-style:none; margin-top:10px;">
									<tr>
										<td>
											<table width="100%" style="color:#000000;">
												<tr>
													<td width="55%"><b>Username</b></td>
													<td width="6px"><b>:</b></td>
													<td width="140px"><input id="newUserUsername" type="text"></td>
												</tr>
												<tr>
													<td><b>Password</b></td>
													<td><b>:</b></td>
													<td><input id="newUserPassword" type="password"></td>
												</tr>
												<tr>
													<td><b>Confirm Password</b></td>
													<td><b>:</b></td>
													<td><input id="newUserConfirmPassword" type="password"></td>
												</tr>
												<tr>
													<td style="font-size:80%;"><b>Administrative Privileges</b></td>
													<td><b>:</b></td>
													<td style="text-align:left;padding-left:10px;"><input type="checkbox" id="newUserAdminPrivileges"/></td>
												</tr>
												<tr>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
												</tr>
											</table>
										</td>
									</tr>

									<tr>
										<td align="center"><button onclick="addNewUser()"><b style="color:#000000">Add New User</b></button></td>
									</tr>
								</table>
								<br>
								<div id="statusNewUser" style="color:red"></div>
							</fieldset>
						</td>
						<td>&nbsp;&nbsp;&nbsp;</td>
						<td>
							<!--<button onclick="reloadUsers()">Refresh</button>-->
							<div style="padding:5px; margin-top:25px; background-color:#565051; color: #F2F2F2; cursor: pointer;" id="list_of_users"><font size="4"><b>List of Users</b></font></div>
							
							<ul id="users_menu">
								<!-- LIST OF SCHEDULES GOES HERE -->
							</ul>
						</td>
					</tr>
RTD;
					//check if user is a root
					$currentUserName = $_SESSION["myusername"];
					$userData = mysql_query("SELECT * FROM `user` WHERE username = '$currentUserName';--");
					$userData = mysql_fetch_array($userData);
					
					if($userData['root']){
						echo "$rootSettings";
					}
					?>
					<!------------------------------------------------------->
				</table>
				</div>
				
				<!----------------------------------------------------------------------------------------------------------------------------------->

				<div id="system_settings">
					<fieldset class="ui-corner-all" align="center" width="100%" style="margin:10px 20px; padding:0 20px;">
					<legend align="center"><h3 style="color: #439deb;">Change Node Names</h3></legend>
					<!-- START CHANGE NODES NAMES -->
					<div class="clear"></div>
					<button style="margin:10px 20px;" id="clickme" onclick="loadNames()">&#8595;&nbsp;Load Nodes&nbsp;&#8595;</button>
					<div align="center" style="margin:10px 20px;" id="nodeName"></div>

					<br>
					<!-- END CHANGE NODES NAMES -->
					</fieldset>
					
					<fieldset align="left" width="100%" style="margin:10px 20px; padding:0 20px;">
					<legend>Database table 'nodes'</legend>
					<div align="center" id="dbCheckVal" value="not pass"></div>
					</fieldset>
				</div>
				
				<div id="log_settings">
					
					<fieldset align="center" width="100%" style="margin:10px 20px; padding:15px 20px; color:#cc6600">
					<b>Please note:</b> If at the end of the Log file you see "Log End : [time]", therefore the Server is NOT running.
					</fieldset>
				
					<fieldset align="center" width="100%" style="margin:10px 20px; padding:15px 20px;">
					<legend>Server 1</legend>
					<button id="server_1" onclick="server_1()">Start SERVER 1</button>
					<button id="server_1_stop" onclick="server_1_stop()">Stop SERVER 1</button>
					<button><a id="log_down" href="/log_file.log" target="_blank">Download Full Log</a></button>
					<br><br>
					<div id="server_1_stop_div" style="background-color:#F5F5F5;width:250px;"></div>
					<div id="server_1_div" style="height:500px;width:880px;font:16px/26px Georgia, Garamond, Serif; overflow:scroll;background-color:#F5F5F5;"></div>
					</fieldset>
				</div>
				
				<div id="xml_settings">
					
					
					<fieldset align="center" width="100%" style="margin:10px 20px; padding:15px 20px; color:#cc6600">
					<button id="xmlHASH" onclick="xmlHASH()">Get Hash of XML</button>
					<div id="hash" style="background-color:#F5F5F5; display:inline"></div>
					</fieldset>
					
					<fieldset align="center" width="100%" style="margin:10px 20px; padding:15px 20px; color:#cc6600">
					<button id="remove_file" onclick="remove_file()">Remove XML File</button><br><b>Warning: Database will be reloaded too.</b>
					</fieldset>
					
					<fieldset align="left" width="100%" style="margin:10px 20px; padding:15px 20px;">
					<legend>XML Data</legend>
					<button id="getXMLData" onclick="getXMLData()">Get XML Data</button>
					<div id="xmlData" style="background-color:#F5F5F5;"></div>
					</fieldset>
					<button id="viewPort" onclick="viewPort()">View Port</button>
				</div>

<script>				
$(document).ready(function() {
	reloadUsers();
});
</script>
				
			</div>
			<!-- END BODY OF CONTAINER WITHIN CENTER TAG -->
		</center>
	</div><!-- end .container -->
	<div class="push"></div>
</div><!-- end #wrapper -->
<?php include("../includes/footer.php"); ?>
</body>
</html>
