<?php

require_once '/var/www/connection.php';

$data = new dataClass();

if(isset($_POST['action']) && !empty($_POST['action'])) {
    $action = $_POST['action'];
    switch($action) {
		case 'loadNodeData' : $data->loadNodeData(); break;
		case 'updateGroup' : $data->updateGroup(); break;
		case 'create_new_group' : $data->create_new_group(); break;
		case 'remove_group' : $data->remove_group(); break;
		
		case 'remove_from_groups' : $data->remove_from_groups(); break;
		case 'add_n_to_g' : $data->add_n_to_g(); break;
		
    }
}
//startPHP
//////////////////checkLoadNames
class dataClass
{	

	public function add_n_to_g(){
	
		$nid = $_POST['nid'];
		$gname = $_POST['gname'];
		
		
		$nid = str_replace("addsn", "", $nid);
		$gname = str_replace("addsg", "", $gname);
		
		//remove node from nodes_in_group - overwrite
		$result = mysql_query("DELETE FROM `nodes_in_group` WHERE node_id = '$nid'");
		
		$group_id = mysql_query("SELECT group_id FROM groups WHERE name='$gname'");
		if($group_id = mysql_fetch_array($group_id)){
			
			$group_id = $group_id['group_id'];
			
			$updateQuery = "INSERT INTO `nodes_in_group`(`group_id`, `node_id`) VALUES ('$group_id', '$nid')";
			mysql_query($updateQuery);
			
			echo "Node(id=$nid) successfully added to a group '$gname'";

		}
		else{
			echo "Unexpected Error.";
		}
		

		
		/*
		$group_name = mysql_query("SELECT node_id FROM nodes_in_group WHERE node_id='$nid'");
		if(($group_name = mysql_fetch_array($group_name)) > 0){
			$result = mysql_query("DELETE FROM `nodes_in_group` WHERE node_id = '$nid'");
			if($result)
				echo "Node Successfully Removed.";
			else
				echo "Error, Node Either doesn't exist or can't be removed.";
			
		}
		else{
			echo "Node doesn't belong to any group.";
		}
		*/
		//echo "Got it: nid: $nid + gname: $gname.";
	}

	public function remove_from_groups(){
	
		$nid = $_POST['nid'];
		$nid = str_replace("rem", "", $nid);
		
		$group_name = mysql_query("SELECT node_id FROM nodes_in_group WHERE node_id='$nid'");
		if(($group_name = mysql_fetch_array($group_name)) > 0){
			$result = mysql_query("DELETE FROM `nodes_in_group` WHERE node_id = '$nid'");
			if($result)
				echo "Node Successfully Removed.";
			else
				echo "Error, Node Either doesn't exist or can't be removed.";
			
		}
		else{
			echo "Node doesn't belong to any group.";
		}
	}

	public function remove_group(){
	
		$group_name = $_POST['group'];
		$result = mysql_query("DELETE FROM `groups` WHERE name = '$group_name'");
		if($result)
			echo "Group '$group_name' is removed.";
		else
			echo "Unexpected Error. Can't remove group '$group_name'";
	}

	public function create_new_group(){
	
		$newGroup = $_POST['newGroup'];
		
		$newGroup = stripslashes($newGroup);
		$newGroup = stripslashes($newGroup);
		$order   = array(";", "--", "node_");
		$replace = '';
		$newGroup = str_replace($order, $replace, $newGroup);
		
		if(strlen($newGroup) > 0){
			$group_name = mysql_query("SELECT name FROM groups WHERE name='$newGroup'");
			if(!($group_name = mysql_fetch_array($group_name))){
				$updateQuery = "INSERT INTO `groups`(`name`) VALUES ('$newGroup')";
				mysql_query($updateQuery);
				echo "Group '$newGroup' is created.";
			}
			else{
				echo "Can't create. Specified group name already exist.";
			}
		}
		else{
			echo "Group name must be at least 1 character long.";
		}		
	}
	
	public function updateGroup(){
	
		$id = $_POST['device_id'];
		$groupName = $_POST['group'];

		$group_id = mysql_query("SELECT group_id FROM groups WHERE name='$groupName'");
		if($group_id = mysql_fetch_array($group_id)){
			
			$group_id = $group_id['group_id'];
			
			$updateQuery = "UPDATE `nodes_in_group` SET `group_id`= '$group_id' WHERE `node_id` = '$id'";
			mysql_query($updateQuery);
		}

		echo "New group for Node $id is $groupName";
	}
	
	
	public function loadNodeData(){
		
		$id = $_POST['device_id'];
		$id = str_replace("curr", "", $id);
		//NAME----------------------------
		$name = "Not Defined";
		$node_names_sql = mysql_query("SELECT * FROM node_names WHERE node_id='$id'");
		if($node_name = mysql_fetch_array($node_names_sql))
			$name = $node_name['name'];
		//NAME----------------------------
		
		//TYPE----------------------------
		$type = "Type Not Found";
		
		$commandClasses = mysql_query("SELECT * FROM command_classes WHERE node_id = '$id'");
		
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
		//TYPE----------------------------
		
		//Group----------------------------
		$group = "Not Defined";
		$node_groups_sql = mysql_query("SELECT group_id FROM nodes_in_group WHERE node_id='$id'");
		if($group_id = mysql_fetch_array($node_groups_sql)){
			$group_id = $group_id['group_id'];
			$group_id = mysql_query("SELECT name FROM groups WHERE group_id='$group_id'");
			$node_name = mysql_fetch_array($group_id);
			$gname = $node_name['name'];
		}
		//Group----------------------------
		
		
		$table_1 = <<<TBL1
		<script type="text/javascript" src="../includes/zwave_scripts.js"></script>
		<table id="devices_table">
			<tr>
				<td>
					Device ID:
				</td>
				<td>
					<input type="text" style="width:25px;text-align:center;" id="device_id" value="{$id}" readonly/>
				</td>
			</tr>
			<tr>
				<td>
					Device Name:
				</td>
				<td>
					<input type="text" style="width:150px;" id="device_name" value="{$name}" readonly/>
				</td>
			</tr>
			<tr>
				<td>
					Device Type:
				</td>
				<td>
					<input type="text" style="width:150px;" id="device_type" value="{$type}" readonly/>
				</td>
			</tr>
			<tr>
				<td>
					Current Group:
				</td>
				<td>
					<input type="text" style="width:150px;" id="device_group" value="{$gname}" readonly/>
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
TBL1;

			
		
		$table_2 = "";
		//Check if name exist	
		$group_names = mysql_query("SELECT * FROM groups");
		
		while($row_names = mysql_fetch_array($group_names)){
			if($row_names['name']){
				$curr_db_name = $row_names['name'];
			}
			$table_2 = $table_2 . "<option value=\"$curr_db_name\">$curr_db_name</option>";
		}
		$table_2 = $table_2 .  "
		<script>
		$(document).ready(function() {
			$('#device_update').click(function() {
				//alert($('#groups_select option:selected').text());
				//alert($('#groups_select option:selected').val());
				var id = $('#device_id').val();
				updateGroup(id ,$('#groups_select option:selected').val());
			});
		});
		
		
		function updateGroup(id, group)
		{	
		
			$.post(\"functions/changeGroups.php\", { action: \"updateGroup\", device_id: id, group: group},
				function(data) {
					alert(data);
					location.reload();
					//$('#device_content').html(data);
				});
			//location.reload();
			//alert(id + group);
		}
		
		</script>";
		
		$table_3 = <<<TBL3
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
TBL3;
		
		
		echo $table_1 . $table_2 . $table_3;
		//echo "bla";
		
		

	}





}



?>