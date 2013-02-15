<?php

require_once '/var/www/connection.php';

$data = new dataClass();

if(isset($_POST['action']) && !empty($_POST['action'])) {
    $action = $_POST['action'];
    switch($action) {

		case 'add_rule' : $data->add_rule(); break;
		case 'refreshRules' : $data->refreshRules(); break;
		case 'removeRule' : $data->removeRule(); break;
		case 'updateIndex' : $data->updateIndex(); break;
		
    }
}
//startPHP  
//////////////////checkLoadNames
class dataClass
{	
	
	public function updateIndex(){
	
		$id = $_POST['id'];
		$index = $_POST['index'];
		
		$updateQuery = "UPDATE `rules` SET `index`= '$index' WHERE `id` = '$id'";
		mysql_query($updateQuery);

	}
	
	
	public function removeRule(){
	
		$id = $_POST['id'];
		
		$rules = mysql_query("SELECT * FROM rules WHERE id = '$id'");
		$index = mysql_fetch_array($rules);
		$index = (int)$index['index'];
		//echo "$index";
		
		$temp = "";
		
		$updateQuery = "DELETE FROM `rules` WHERE `id` = '$id'";
		mysql_query($updateQuery);

		//update indexes
		$rules = mysql_query("SELECT * FROM rules");
		while($newIndex = mysql_fetch_array($rules)){
			if(((int)$newIndex['index']) > $index){
				
				$currID = $newIndex['id'];
				
				$currIndex = (int)$newIndex['index'];
				$newIndex = $currIndex - 1;
				$temp = $temp . $currID . "-" . $currIndex . "-" . $newIndex . " | ";
				
				$updateQuery = "UPDATE `rules` SET `index`= '$newIndex' WHERE `id` = '$currID'";
				mysql_query($updateQuery);
			}
		}
		
		echo "$temp";

		//DELETE FROM `rules` WHERE 1
	}
	
	
	public function refreshRules(){
	
					//Check if name exist	SELECT * FROM  `rules` ORDER BY  `rules`.`index` ASC 
					$rules = mysql_query("SELECT * FROM  `rules` ORDER BY  `rules`.`index` ASC ");
					$rules_exist = 0;
					$li_rules = "";
					$header = <<<LIB

<script type="text/javascript" src="../includes/jquery-1.7.2.js"></script>
<script>
$(document).ready(function() {
$("button").button();
});
</script>

<li class="header_li" style="background-color:#565051;">
<table class="add_rule" style="text-align:center;  font-size:10px;">
	<tr>
		<td style="width:25px;" title="Node ID">
			ID
		</td>
		<td style="width:130px; ">
			Node Name
		</td>
		<td style="width:45px; ">
			Is ON
		</td>
		<td style="width:25px; " title="Minimum Level">
			min
		</td>
		<td style="width:25px; " title="Maximum Level">
			max
		</td>
		<td style="width:45px; ">
			||
		</td>
		<td style="width:25px;" title="Node ID">
			ID
		</td>
		<td style="width:125px; ">
			Node Name
		</td>
		<td style="width:45px; ">
			Is ON
		</td>
		<td style="width:25px; ">
			Level
		</td>
		<td style="width:100px;" >
			<!--<input type="text" name=myText value="Enter Your Name" readonly>-->
		</td>
		<td style="width:80px;" title="1 has highest priority\nRules Execution Order: Bottom to Top">
			Priority
		</td>
	</tr>
</table>
</li>				
LIB;
					$index_id = 1;
					while($row_names = mysql_fetch_array($rules)){
					

					
					
						$rules_exist = 1;
						$if_nid = $row_names['if_nid'];
						$then_nid = $row_names['then_nid'];
						
						//NAME----------------------------
						$if_name = "Not Defined";
						$node_names_sql = mysql_query("SELECT * FROM node_names WHERE node_id='$if_nid'");
						if($node_name = mysql_fetch_array($node_names_sql))
							$if_name = $node_name['name'];
						//NAME----------------------------
						
						//NAME----------------------------
						$then_name = "Not Defined";
						$node_names_sql = mysql_query("SELECT * FROM node_names WHERE node_id='$then_nid'");
						if($node_name = mysql_fetch_array($node_names_sql))
							$then_name = $node_name['name'];
						//NAME----------------------------
						
						$if_isOn = $row_names['if_isON'];
						$if_minLevel = $row_names['if_minLevel'];
						$if_maxLevel = $row_names['if_maxLevel'];
						
						$then_isOn = $row_names['then_isON'];
						$then_Level = $row_names['then_Level'];
						
						$li_id = $if_nid . $if_isOn . $if_minLevel . $if_maxLevel . $then_nid;
						$input_id = $if_nid . $if_isOn . $if_minLevel . $if_maxLevel . $then_nid . $then_isOn . $then_Level;
						
						$temp = <<<LIB

<li id="{$li_id}" class="ui-state-default">
<table class="add_rule" style="text-align:center; font-size:10px;">
	<tr>
		<td style="width:25px; ">
			<input style="width:25px;text-align:center;" type="text" value="{$if_nid}" readonly>
		</td>
		<td style="width:120px;">
			<input style="width:115px;text-align:center;" type="text" value="{$if_name}" readonly>
		</td>
		<td style="width:45px;">
			<input style="width:40px;text-align:center;" type="text" value="{$if_isOn}" readonly>
		</td>
		<td style="width:25px;">
			<input style="width:20px;text-align:center;" type="text" value="{$if_minLevel}" readonly>
		</td>
		<td style="width:25px;">
			<input style="width:20px;text-align:center;" type="text" value="{$if_maxLevel}" readonly>
		</td>
		<td style="width:45px;color:#000000;">
			||
		</td>
		<td style="width:25px; ">
			<input style="width:25px;text-align:center;" type="text" value="{$then_nid}" readonly>
		</td>
		<td style="width:120px;">
			<input style="width:115px;text-align:center;" type="text" value="{$then_name}" readonly>
		</td>
		<td style="width:45px;">
			<input style="width:40px;text-align:center;" type="text" value="{$then_isOn}" readonly>
		</td>
		<td style="width:25px;">
			<input style="width:20px;text-align:center;" type="text" value="{$then_Level}" readonly>
		</td>
		<td style="font-size:medium; width:100px;">
			<button name="{$li_id}" onClick="remove(this.name)">Remove</button>
		</td>
		<td style="font-size:medium; width:80px;">
			<input class="special" id="{$input_id}" style="width:25px;text-align:center;" type="text" value="{$index_id}" readonly>
		</td>
	</tr>
</table>
</li>				
LIB;
						
						
						
						
						
						
						$li_rules = $li_rules . $temp;
						$index_id++;
					}
					
					if(!$rules_exist){
						echo "<li>No rules appear to be in database.</li>";
					}
					else{
						echo $header . $li_rules;
					}
					

	}
	
	public function add_rule(){
	
		//if_nid + "|" + if_isOn + "|" + if_minLevel + "|" + if_maxLevel + "|" + then_nid + "|" + then_isOn + "|" + then_Level
	
		$if_nid = $_POST['if_nid'];
		$then_nid = $_POST['then_nid'];
		
		$if_isOn = $_POST['if_isOn'];
		$if_minLevel = $_POST['if_minLevel'];
		$if_maxLevel = $_POST['if_maxLevel'];
		
		$then_isOn = $_POST['then_isOn'];
		$then_Level = $_POST['then_Level'];
		
		
		$if_nid = str_replace("if_n_id_", "", $if_nid);
		$then_nid = str_replace("then_n_id_", "", $then_nid);
		
		$id = $if_nid . $if_isOn . $if_minLevel . $if_maxLevel . $then_nid;
		
		$rules_count = 0;
		$rules = mysql_query("SELECT * FROM rules");
		while(mysql_fetch_array($rules)){
			$rules_count++;
		}
		$rules_count++;
		
		$updateQuery = "INSERT INTO `rules`(`id`, `if_nid`, `if_isON`, `if_minLevel`, `if_maxLevel`, `then_nid`, `then_isON`, `then_Level`, `index`) VALUES ('$id','$if_nid','$if_isOn','$if_minLevel','$if_maxLevel','$then_nid','$then_isOn','$then_Level','$rules_count')";
		if(mysql_query($updateQuery))
			echo "Rule Added";
		else{
			echo "Same or similar rule already exist.";
		}
		
		//echo "$if_nid | $if_isOn | $if_minLevel | $if_maxLevel | $then_nid | $then_isOn | $then_Level";

	}






}



?>