<?php
session_start();
if(!isset($_SESSION["myusername"])){
header("location:/Logout.php");//clean session and return to index.php
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../includes/imports.php"); ?>
<?php require_once '../connection.php'; ?>
<style>
#tabs { margin: 1em 0 0 0; border-bottom: .1em solid #000000;}
#tabs li { display: inline; width: 79px; padding: 2px 8px 2px 8px; border: 1px solid #4a7194; margin-left: .5em; text-decoration: none; cursor: pointer; background:#efefef;}
#tabs li:hover {background:#bbbbbb;}

table#devices_table td { padding: 12px; line-height:30px; width="120px;"}
#groups_menu li {text-decoration: none; display:block; cursor: pointer; padding:5px; background-color:#565051; color: #30b6c9; margin-top:7px;}
#groups_settings_table td {line-height:40px; padding: 0 10px;}

</style>
<script>

//hide unused buttons

$(document).ready(function() {
	$('#groups_tab').click(function() {
	
	  $('#groups_div').show();
	  $('#settings_div').hide();
	  
	  $('#groups_tab').css('font-weight', 'bold');
	  $('#settings_tab').css('font-weight', 'normal');

	});
});


$(document).ready(function() {
	$('#settings_tab').click(function() {
	
	  $('#groups_div').hide();
	  $('#settings_div').show();

	  
	  $('#groups_tab').css('font-weight', 'normal');
	  $('#settings_tab').css('font-weight', 'bold');


	});
});

$(document).ready(function() {
	$('#groups_div').show();
	$('#settings_div').hide();
	
	$('#groups_tab').css('font-weight', 'bold');
	$('#settings_tab').css('font-weight', 'normal');
});

$(document).ready(function() {
	$('#groups_menu li').click(function() {
	
		$(this).next('div').slideToggle('slow', function() {
		});
		
	});
	
	
	$('.nodesids li').click(function() {
		//alert(this.id);
		loadNodeData(this.id);
		//$('#device_content').html(this.id);
		//device_content
	});
	
	
	
	$('#remove_group_btn').click(function() {
		var group = $('#remove_group_select option:selected').val();
		//alert(group);
		remove_group(group);
	});
	
	$('#create_new_group_btn').click(function() {
		var newGroup = $('#create_new_group_txt').val();
		//var newGroup = $('#create_new_group_txt').text();
		//updateGroup(id ,$('#groups_select option:selected').val());
		create_new_group(newGroup);
	});
	
	$('#remove_from_groups_btn').click(function() {
		var nid = $('#remove_node_from_group_select option:selected').val();
		remove_from_groups(nid);
	});
	//888888888888888888888888888888888888888888888
	$('#add_n_to_g').click(function() {
		// 
		var nid = $('#add_node_node_select option:selected').val();
		var gname = $('#add_node_group_select option:selected').val();
		add_n_to_g(nid, gname);
		//alert("Node ID: " + nid + "\nGroup: " + gname);
	});	
	//888888888888888888888888888888888888888888888
});

function add_n_to_g(nid, gname)
{	
	$.post("functions/changeGroups.php", { action: "add_n_to_g", nid: nid, gname: gname},
		function(data) {
			alert(data);
			location.reload();
			//$('#device_content').html(data);
		});
}

function remove_from_groups(nid)
{	
	$.post("functions/changeGroups.php", { action: "remove_from_groups", nid: nid},
		function(data) {
			alert(data);
			location.reload();
			//$('#device_content').html(data);
		});
}	

function loadNodeData(id)
{	
	$.post("functions/changeGroups.php", { action: "loadNodeData", device_id: id},
		function(data) {
			//alert("Data Loaded: " + data);
			$('#device_content').html(data);
		});
	
}

function create_new_group(newGroup)
{	
	$.post("functions/changeGroups.php", { action: "create_new_group", newGroup: newGroup},
		function(data) {
			alert(data);
			location.reload();
			//$('#device_content').html(data);
		});
}

function remove_group(group)
{	
	$.post("functions/changeGroups.php", { action: "remove_group", group: group},
		function(data) {
			alert(data);
			location.reload();
			//$('#device_content').html(data);
		});
}	

</script>
<title>BetaHomes</title>
</head>
<body>
<div id="wrapper">
	<div class="container" style="border-style:groove;">
		<?php include("../includes/header.php"); ?>
			<center>
			<!--<img src="/images/pr-photo.png" alt="" width="15%" height="15%" />-->
			<ul id="tabs">
			  <li id="groups_tab">Groups</li>
			  <li id="settings_tab" >Settings</li>
			</ul>
			<!-- START BODY OF CONTAINER WITHIN CENTER TAG -->
			<div id="divBorder">

				
				<div id="groups_div">
				
					<table align="center" width="100%" style="background-color:#D4D4D4; color: #000000; text-align:left;">
						<tr>
							<td width="22%" style="padding:7px;vertical-align:top;">
								<div style="padding:5px; background-color:#565051; color: #F2F2F2;"><font size="4"><b>Groups</b></font></div>
								<ul id="groups_menu">
									<?php
										//Check if name exist	
										$group_names = mysql_query("SELECT * FROM groups");
										
										while($row_names = mysql_fetch_array($group_names)){
											if($row_names['name']){
												$curr_db_name = $row_names['name'];
											}
											echo "<li id=\"cluster$curr_db_name\" ><b>$curr_db_name</b></li>";
											//<div id=\"bla\">Hello</div>
											
											$gid = $row_names['group_id'];
											$nodes_in_group = mysql_query("SELECT * FROM nodes_in_group WHERE group_id='$gid'");
											echo "<div id=\"nodes$gid\" class=\"nodesids\" >";
											while($node_in_group = mysql_fetch_array($nodes_in_group)){
												$nid = $node_in_group['node_id'];
												
												$name = "Not Defined";
												$node_names_sql = mysql_query("SELECT * FROM node_names WHERE node_id='$nid'");
												if($node_name = mysql_fetch_array($node_names_sql))
													$name = $node_name['name'];
												
												echo "<li id=\"curr{$nid}\" style=\"padding-left:20px;color:#F2F2F2\">$name</li>";
												
											}
											echo "</div>";
											
										}
									?>
								</ul>
							</td>
							<td width="78%" style="padding-top:7px;padding-right:7px;padding-bottom:7px;vertical-align:top;">
								<div style="padding:5px; background-color:#565051; color: #F2F2F2;"><font size="4"><b>Device</b></font></div>
								<div id="device_content" style="margin-top:7px; padding:5px; background-color:#F2F2F2; color: #565051;">
									<table id="devices_table">
										<tr>
											<td>
												Device ID:
											</td>
											<td>
												<input type="text" style="width:25px;text-align:center;" id="device_id" disabled="disabled" value=""/>
											</td>
										</tr>
										<tr>
											<td>
												Device Name:
											</td>
											<td>
												<input type="text" style="width:150px;" id="device_name" disabled="disabled" value=""/>
											</td>
										</tr>
										<tr>
											<td>
												Device Type:
											</td>
											<td>
												<input type="text" style="width:150px;" id="device_type" disabled="disabled" value=""/>
											</td>
										</tr>
										<tr>
											<td>
												Current Group:
											</td>
											<td>
												<input type="text" style="width:150px;" id="device_group" disabled="disabled" value=""/>
											</td>
										</tr>
										
										<tr>
											<td>
												New Group:

											</td>
											<td>
												<fieldset width="100%" class="ui-corner-all">
													<table>
														<tr>
															<td>
																<select id="groups_select">
																	<?php

																		//Check if name exist	
																		$group_names = mysql_query("SELECT * FROM groups");
																		
																		while($row_names = mysql_fetch_array($group_names)){
																			if($row_names['name']){
																				$curr_db_name = $row_names['name'];
																			}
																			echo "<option value=\"$curr_db_name\">$curr_db_name</option>";
																		}
																	?>
																</select>
															</td>
														</tr>
														<tr>
															<td>
																<button id="device_update">Update</button>
															</td>
														</tr>
													</table>
												</fieldset>
											</td>
										</tr>
									</table>
								</div>
							</td>
						</tr>
					</table>
				</div>
				
				
				<div id="settings_div" >
					
					<fieldset class="ui-corner-all" style="width:550px;padding:25px 0;margin-top:15px;" >
						<table id="groups_settings_table" style="line-height:20px;">
							<tr>
								<td>
									Create New Group:
								</td>
								<td>
									<input type="text" style="width:150px;text-align:center;" id="create_new_group_txt" value=""/>
								</td>
								<td>
									<button id="create_new_group_btn">Create</button>
								</td>
							</tr>
							<tr>
								<td>
									Remove Group:
								</td>
								<td>
									<select style="width:150px;" id="remove_group_select">
										<?php

											//Check if name exist	
											$group_names = mysql_query("SELECT * FROM groups");
											
											while($row_names = mysql_fetch_array($group_names)){
												if($row_names['name']){
													$curr_db_name = $row_names['name'];
												}
												echo "<option value=\"$curr_db_name\">$curr_db_name</option>";
											}
										?>
									</select>
								</td>
								<td>
									<button id="remove_group_btn">Remove</button>
								</td>
							</tr>
							
						</table>
					</fieldset>
					
					<!------------------------------------------------------------------------------------>
					
					<fieldset class="ui-corner-all" style="width:700px;padding:25px 0;margin-top:15px;" >
						<table id="groups_settings_table" style="line-height:20px;">
							<tr>
								<td>
									Remove Node from Groups:
								</td>
								<td>
									<select style="width:150px;" id="remove_node_from_group_select">
										<?php

											//Check if name exist	
											$nodes = mysql_query("SELECT * FROM nodes");
											
											while($node_id = mysql_fetch_array($nodes)){
												$node_id = $node_id['node_id'];
												
												$node_names = mysql_query("SELECT name FROM node_names WHERE node_id = '$node_id'");
												
												if($node_name = mysql_fetch_array($node_names)){
													$node_name = $node_name['name'];
												}
												else{
													$node_name = "Name Not Defined";
												}
												
												echo "<option value=\"rem$node_id\">$node_name</option>";
											}
										?>
									</select>
								</td>
								<td>
									<button id="remove_from_groups_btn">Remove</button>
								</td>
							</tr>
						</table>
					</fieldset>
					<fieldset class="ui-corner-all" style="width:850px;padding:25px 0;margin-top:15px;" >
						<table style="text-align:center;">
							<tr>
								<td>
									
								</td>
								<td style="font-size:70%;">
									Select Node
								</td>
								<td style="font-size:70%;">
									Select Group
								</td>
								<td>
									
								</td>
							</tr>
							<tr>
								<td style="padding:0 10px;">
									Add Node to a Group:
								</td>
								<td style="padding:0 10px;">
									<select style="width:150px;" id="add_node_node_select">
										<?php

											//Check if name exist	
											$nodes = mysql_query("SELECT * FROM nodes");
											
											while($node_id = mysql_fetch_array($nodes)){
												$node_id = $node_id['node_id'];
												
												$node_names = mysql_query("SELECT name FROM node_names WHERE node_id = '$node_id'");
												
												if($node_name = mysql_fetch_array($node_names)){
													$node_name = $node_name['name'];
												}
												else{
													$node_name = "Name Not Defined";
												}
												
												echo "<option value=\"addsn$node_id\">$node_name</option>";
											}
										?>
									</select>
								</td>
								<td>
									<select style="width:150px;" id="add_node_group_select">
										<?php

											//Check if name exist	
											$group_names = mysql_query("SELECT * FROM groups");
											
											while($row_names = mysql_fetch_array($group_names)){
												if($row_names['name']){
													$curr_db_name = $row_names['name'];
												}
												echo "<option value=\"addsg$curr_db_name\">$curr_db_name</option>";
											}
										?>
									</select>
								</td>
								<td style="padding:0 10px;">
									<button id="add_n_to_g">Add</button>
								</td>
							</tr>
						</table>
					</fieldset>
					
				</div>
				
				
				
				
				
				
				
				
				
			</div>
			<!-- END BODY OF CONTAINER WITHIN CENTER TAG -->
		</center>
	</div><!-- end .container -->
	<div class="push"></div>
</div><!-- end #wrapper -->
<?php include("../includes/footer.php"); ?>

</body>
</html>
