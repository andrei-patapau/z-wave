<?php

require_once '/var/www/connection.php';

$data = new dataClass();

if(isset($_POST['action']) && !empty($_POST['action'])) {
    $action = $_POST['action'];
    switch($action) {
	
		case 'addSchedule' : $data->addSchedule(); break;
		case 'refreshSchedules' : $data->refreshSchedules(); break;
		case 'activeOrNot' : $data->activeOrNot(); break;
		case 'deleteSchedule' : $data->deleteSchedule(); break;
		
    }
}
//startPHP
//////////////////checkLoadNames
class dataClass
{	

	public function deleteSchedule(){
	
		$schedule = $_POST['deleteSchedule'];
		
		
		$typeAndIdArray = explode ( "_" , $schedule );
		
		$type = $typeAndIdArray[0];
		$id = $typeAndIdArray[1];
		
		if($type == "onceBox"){
			$updateQuery = "DELETE FROM `schedulesOnce` WHERE `id` = '$id'";
			mysql_query($updateQuery);
			
			//echo "$type | $id \n $active";
		}
		else if($type == "everyDayBox"){
			$updateQuery = "DELETE FROM `schedulesEveryDay` WHERE `id` = '$id'";
			mysql_query($updateQuery);
		}
		else if($type == "weeklyBox"){
			$updateQuery = "DELETE FROM `schedulesWeekly` WHERE `id` = '$id'";
			mysql_query($updateQuery);
		}
		else if($type == "intervalBox"){
			$updateQuery = "DELETE FROM `schedulesInterval` WHERE `id` = '$id'";
			mysql_query($updateQuery);
		}
		
	
		//echo "$type | $id \n $active";
	}


	public function activeOrNot(){
	
		$schedulesActive = $_POST['schedulesActive'];
		
		$typeAndId = $schedulesActive[0];
		
		if($schedulesActive[1] == "true")
			$active = 1;
		else
			$active = 0;
		
		
		
		$typeAndIdArray = explode ( "_" , $typeAndId );
		
		$type = $typeAndIdArray[0];
		$id = $typeAndIdArray[1];
		
		if($type == "onceBox"){
			$updateQuery = "UPDATE `schedulesOnce` SET `active`= '$active' WHERE `id` = '$id'";
			mysql_query($updateQuery);
			//echo "$type | $id \n $active";
		}
		else if($type == "everyDayBox"){
			$updateQuery = "UPDATE `schedulesEveryDay` SET `active`= '$active' WHERE `id` = '$id'";
			mysql_query($updateQuery);
		}
		else if($type == "weeklyBox"){
			$updateQuery = "UPDATE `schedulesWeekly` SET `active`= '$active' WHERE `id` = '$id'";
			mysql_query($updateQuery);
		}
		else if($type == "intervalBox"){
			$updateQuery = "UPDATE `schedulesInterval` SET `active`= '$active' WHERE `id` = '$id'";
			mysql_query($updateQuery);
		}
		
	
		//echo "$type | $id \n $active";
	}



//activeOrNot
	
	public function refreshSchedules(){
		
		$schedulesOnce = mysql_query("SELECT * FROM `schedulesOnce`");
		$schedulesOnce_li = "";
		
		$schedulesEveryDay = mysql_query("SELECT * FROM `schedulesEveryDay`");
		$schedulesEveryDay_li = "";
		
		$schedulesWeekly = mysql_query("SELECT * FROM `schedulesWeekly`");
		$schedulesWeekly_li = "";
		
		$schedulesInterval = mysql_query("SELECT * FROM `schedulesInterval`");
		$schedulesInterval_li = "";
		
		
		//ONCE
		while($row_schedulesOnce = mysql_fetch_array($schedulesOnce)){
			//`title`, `timeHH`, `timeMM`, `timeAMPM`, `date`, `remove`, `active`, `nid`, `level`, `isON`
			
			$title = $row_schedulesOnce['title'];
			
			$timeHH = $row_schedulesOnce['timeHH'];
			$timeMM = $row_schedulesOnce['timeMM'];
			
			$date = $row_schedulesOnce['date'];
			
			$nid = $row_schedulesOnce['nid'];
			
			//NAME----------------------------
			$name = "Not Defined";
			$node_names_sql = mysql_query("SELECT * FROM node_names WHERE node_id='$nid'");
			if($node_name = mysql_fetch_array($node_names_sql))
				$name = $node_name['name'];
			//NAME----------------------------
			
			if($row_schedulesOnce['level'] == "-")
				$status = strtoupper($row_schedulesOnce['isON']);
			else
				$status = "level = " . $row_schedulesOnce['level'];
			
			//************************************************************************************************
			//standard setup above
			
			//check if active
			if($row_schedulesOnce['active'] == "1")
				$checked = "checked";
			else
				$checked = "";
			
			$scheduleID = "onceBox_" . $row_schedulesOnce['id'];
			
			$content = <<<CTN
<li class="ui-state-default" title="{$title}">
	<table>
		<tr>
			<td width="25px">
				<input type="checkbox" id="{$scheduleID}" title="Active / Inactive" onchange="activeFalse(this.id)" {$checked}>
			</td>
			<td width="840px">
				only at {$timeHH}:{$timeMM} on {$date} :: Node Name: {$name}(id = {$nid}), {$status}
			</td>
			<td align="right">
				<button name="{$scheduleID}" onclick="removeSchedule(this.name)">Remove</button>
			</td>
		</tr>
	</table>
</li>
CTN;
			
			//<li>only at TIME on DATE</li>
			$schedulesOnce_li = $schedulesOnce_li . $content;
			
		}
		
		//EVERY DAY
		while($row_schedulesEveryDay = mysql_fetch_array($schedulesEveryDay)){
			//`title`, `timeHH`, `timeMM`, `timeAMPM`, `active`, `nid`, `level`, `isON`
			
			$title = $row_schedulesOnce['title'];
			
			$timeHH = $row_schedulesEveryDay['timeHH'];
			$timeMM = $row_schedulesEveryDay['timeMM'];
			
			
			$nid = $row_schedulesEveryDay['nid'];
			
			//NAME----------------------------
			$name = "Not Defined";
			$node_names_sql = mysql_query("SELECT * FROM node_names WHERE node_id='$nid'");
			if($node_name = mysql_fetch_array($node_names_sql))
				$name = $node_name['name'];
			//NAME----------------------------
			
			if($row_schedulesEveryDay['level'] == "-")
				$status = strtoupper($row_schedulesEveryDay['isON']);
			else
				$status = "level = " . $row_schedulesEveryDay['level'];
			//************************************************************************************************
			//standard setup above
			
			//check if active
			if($row_schedulesEveryDay['active'] == "1")
				$checked = "checked";
			else
				$checked = "";
			
			$scheduleID = "everyDayBox_" . $row_schedulesEveryDay['id'];
			
			$content = <<<CTN
<li class="ui-state-default" title="{$title}">
	<table>
		<tr>
			<td width="25px">
				<input type="checkbox" id="{$scheduleID}" title="Active / Inactive" onchange="activeFalse(this.id)" {$checked}>
			</td>
			<td width="840px">
				every day at {$timeHH}:{$timeMM} :: Node Name: {$name}(id = {$nid}), {$status}
			</td>
			<td align="right">
				<button name="{$scheduleID}" onclick="removeSchedule(this.name)">Remove</button>
			</td>
		</tr>
	</table>
</li>
CTN;
			
			//<li>only at TIME on DATE</li>
			$schedulesEveryDay_li = $schedulesEveryDay_li . $content;
			
		}
		
		//WEEKLY
		while($row_schedulesWeekly = mysql_fetch_array($schedulesWeekly)){
			//`title`, `timeHH`, `timeMM`, `timeAMPM`, `mon`, `tue`, `wed`, `thu`, `fri`, `sat`, `sun`, `active`, `nid`, `level`, `isON`
			
			$title = $row_schedulesOnce['title'];
			
			$timeHH = $row_schedulesWeekly['timeHH'];
			$timeMM = $row_schedulesWeekly['timeMM'];
			
			
			$nid = $row_schedulesWeekly['nid'];
			
			//NAME----------------------------
			$name = "Not Defined";
			$node_names_sql = mysql_query("SELECT * FROM node_names WHERE node_id='$nid'");
			if($node_name = mysql_fetch_array($node_names_sql))
				$name = $node_name['name'];
			//NAME----------------------------
			
			if($row_schedulesWeekly['level'] == "-")
				$status = strtoupper($row_schedulesWeekly['isON']);
			else
				$status = "level = " . $row_schedulesWeekly['level'];
			//************************************************************************************************
			//standard setup above
			
				
			$mon = ($row_schedulesWeekly['mon'] == 1) ? "| Monday |" : "";
			$tue = ($row_schedulesWeekly['tue'] == 1) ? "| Tuesday |" : "";
			$wed = ($row_schedulesWeekly['wed'] == 1) ? "| Wednesday |" : "";
			$thu = ($row_schedulesWeekly['thu'] == 1) ? "| Thursday |" : "";
			$fri = ($row_schedulesWeekly['fri'] == 1) ? "| Friday |" : "";
			$sat = ($row_schedulesWeekly['sat'] == 1) ? "| Saturday |" : "";
			$sun = ($row_schedulesWeekly['sun'] == 1) ? "| Sunday |" : "";
			
			$weekDays = "$mon $tue $wed $thu $fri $sat $sun"; 
			
			//check if active
			if($row_schedulesWeekly['active'] == "1")
				$checked = "checked";
			else
				$checked = "";
			
			$scheduleID = "weeklyBox_" . $row_schedulesWeekly['id'];
			
			$content = <<<CTN
<li class="ui-state-default" title="{$title}" >
	<table>
		<tr>
			<td width="25px">
				<input type="checkbox" id="{$scheduleID}" title="Active / Inactive" onchange="activeFalse(this.id)" {$checked}>
			</td>
			<td width="840px">
				weekly at {$timeHH}:{$timeMM} on {$weekDays} :: Node Name: {$name}(id = {$nid}), {$status}
			</td>
			<td align="right">
				<button name="{$scheduleID}" onclick="removeSchedule(this.name)">Remove</button>
			</td>
		</tr>
	</table>
</li>
CTN;
			
			//<li>only at TIME on DATE</li>
			$schedulesWeekly_li = $schedulesWeekly_li . $content;
			
		}
		
		//Fixed Interval
		while($row_schedulesInterval = mysql_fetch_array($schedulesInterval)){
			//`title`, `timeHH`, `timeMM`, `timeAMPM`, `date`, `timeHzDD`, `timeHzHH`, `timeHzMM`, `active`, `nid`, `level`, `isON`
			
			$title = $row_schedulesOnce['title'];
			
			$timeHH = $row_schedulesInterval['timeHH'];
			$timeMM = $row_schedulesInterval['timeMM'];
			
			
			$nid = $row_schedulesInterval['nid'];
			
			//NAME----------------------------
			$name = "Not Defined";
			$node_names_sql = mysql_query("SELECT * FROM node_names WHERE node_id='$nid'");
			if($node_name = mysql_fetch_array($node_names_sql))
				$name = $node_name['name'];
			//NAME----------------------------
			
			if($row_schedulesInterval['level'] == "-")
				$status = strtoupper($row_schedulesInterval['isON']);
			else
				$status = "level = " . $row_schedulesInterval['level'];
				
			//************************************************************************************************
			//standard setup above
			
			$date = $row_schedulesInterval['date'];
			
			$timeHzDD = $row_schedulesInterval['timeHzDD'];
			$timeHzHH = $row_schedulesInterval['timeHzHH'];
			$timeHzMM = $row_schedulesInterval['timeHzMM'];
			
			//check if active
			if($row_schedulesInterval['active'] == "1")
				$checked = "checked";
			else
				$checked = "";
			
			$scheduleID = "intervalBox_" . $row_schedulesInterval['id'];
			
			
			$content = <<<CTN
<li class="ui-state-default" title="{$title}" >
	<table>
		<tr>
			<td width="25px">
				<input type="checkbox" id="{$scheduleID}" title="Active / Inactive" onchange="activeFalse(this.id)" {$checked}>
			</td>
			<td width="840px">
				every {$timeHzDD}d {$timeHzHH}h {$timeHzMM}min since {$timeHH}:{$timeMM} on {$date} :: Node Name: {$name}(id = {$nid}), {$status}
			</td>
			<td align="right">
				<button name="{$scheduleID}" onclick="removeSchedule(this.name)">Remove</button>
			</td>
		</tr>
	</table>
</li>
CTN;
			
			
			//<li>only at TIME on DATE</li>
			$schedulesInterval_li = $schedulesInterval_li . $content;
			
		}
		
					$header = <<<LIB

<script type="text/javascript" src="../includes/jquery-1.7.2.js"></script>
<script>
$(document).ready(function() {
$("button").button();
});
</script>
LIB;
		
		echo "$header $schedulesOnce_li $schedulesEveryDay_li $schedulesWeekly_li $schedulesInterval_li";
		
		
		
	}
	
	
	public function addSchedule(){
		$schedulesData = $_POST['schedulesData'];
		
		$type = $schedulesData[0];
		
		$title = $schedulesData[1];
		$execTimeHr = $schedulesData[2];
		$execTimeMin = $schedulesData[3];
		
		
		if($type == "once"){
			
			$date = $schedulesData[4];
			$checkBox = $schedulesData[5];
			
			$id = $schedulesData[6];
			$isOn = $schedulesData[7];
			$level = $schedulesData[8];
			
			if($id != '1')
				$this->addScheduleOnce($type, $title, $execTimeHr, $execTimeMin, $date, $checkBox, $id, $isOn, $level);
			else
				echo "Error: Controller Can't be added to schedules";
			
			
		}else if($type == "everyDay"){
			
			//none
			$id = $schedulesData[4];
			$isOn = $schedulesData[5];
			$level = $schedulesData[6];
			
			if($id != '1')
				$this->addScheduleEveryDay($type, $title, $execTimeHr, $execTimeMin, $id, $isOn, $level);
			else
				echo "Error: Controller Can't be added to schedules";
			
		}else if($type == "weekly"){
			
			$daysOfWeek = $schedulesData[4];
			$id = $schedulesData[5];
			$isOn = $schedulesData[6];
			$level = $schedulesData[7];
			
			if($id != '1')
				$this->addScheduleWeekly($type, $title, $execTimeHr, $execTimeMin, $daysOfWeek, $id, $isOn, $level);
			else
				echo "Error: Controller Can't be added to schedules";
				
			
		}else if($type == "fixedInterval"){
			
			$date = $schedulesData[4];
			$intervalDays = $schedulesData[5];
			$intervalHours = $schedulesData[6];
			$intervalMinutes = $schedulesData[7];

			$id = $schedulesData[8];
			$isOn = $schedulesData[9];
			$level = $schedulesData[10];
			
			if($id != '1')
				$this->addScheduleFixedInterval($type, $title, $execTimeHr, $execTimeMin, $date, $intervalDays, $intervalHours, $intervalMinutes, $id, $isOn, $level);
			else
				echo "Error: Controller Can't be added to schedules";
			
		}else{
			//error
		}
	}
	
	public function addScheduleOnce($type, $title, $execTimeHr, $execTimeMin, $date, $checkBox, $id, $isOn, $level){
		
		if($checkBox == 'true')
			$checkBox = 1;
		else
			$checkBox = 0;
		
		$valid = true;
		//Check for input errors
		
		$dateArray = explode ( "/" , $date );
		$todayMonth = intval($dateArray[0]);
		$todayDay = intval($dateArray[1]);
		$todayYear = intval($dateArray[2]);
		$date = $todayMonth . "/" . $todayDay . "/" . $todayYear;
		
		if(count($dateArray) != 3)
			$valid = false;
		else{
			$valid = checkdate( $dateArray[0], $dateArray[1], $dateArray[2] );
		}
		
		if($valid){
			$updateQuery = "INSERT INTO `schedulesOnce`(`title`, `timeHH`, `timeMM`, `date`, `remove`, `active`, `nid`, `level`, `isON`) VALUES ('$title', '$execTimeHr', '$execTimeMin', '$date', '$checkBox', '1', '$id', '$level', '$isOn');--";
			mysql_query($updateQuery);
			//echo "Error: $type, $title, $execTimeHr, $execTimeMin, $execTimeAMPM, $date, $checkBox, $id, $isOn, $level";
		}else{
			//echo "Type: $type\nTitle: $title \nTime: $execTimeHr : $execTimeMin $execTimeAMPM \n $date \n $checkBox";
			//echo "Error: Incorrect Date";
			/*
			Array
			(
			[seconds] => 45
			[minutes] => 52
			[hours] => 14
			[mday] => 24
			[wday] => 2
			[mon] => 1
			[year] => 2006
			[yday] => 23
			[weekday] => Tuesday
			[month] => January
			[0] => 1138110765
			)
			*/
			//$getDateFunc = getdate();
			//echo $getDateFunc[mon];
			echo "Error: Incorrect Date";
		}
	}
	
	public function addScheduleEveryDay($type, $title, $execTimeHr, $execTimeMin, $id, $isOn, $level){
		
		$updateQuery = "INSERT INTO `schedulesEveryDay`(`title`, `timeHH`, `timeMM`, `active`, `nid`, `level`, `isON`) VALUES ('$title', '$execTimeHr', '$execTimeMin', '1', '$id', '$level', '$isOn');--";
		mysql_query($updateQuery);
		//echo "Error:$type, $title, $execTimeHr, $execTimeMin, $execTimeAMPM, $id, $isOn, $level";
		//echo "Type: $type\nTitle: $title \nTime: $execTimeHr : $execTimeMin $execTimeAMPM";
		
	}
	
	public function addScheduleWeekly($type, $title, $execTimeHr, $execTimeMin, $daysOfWeek, $id, $isOn, $level){
		

		$mon = ($daysOfWeek[0] == 'true') ? 1 : 0;
		$tue = ($daysOfWeek[1] == 'true') ? 1 : 0;
		$wed = ($daysOfWeek[2] == 'true') ? 1 : 0;
		$thu = ($daysOfWeek[3] == 'true') ? 1 : 0;
		$fri = ($daysOfWeek[4] == 'true') ? 1 : 0;
		$sat = ($daysOfWeek[5] == 'true') ? 1 : 0;
		$sun = ($daysOfWeek[6] == 'true') ? 1 : 0;
		
		
		$updateQuery = "INSERT INTO `schedulesWeekly`(`title`, `timeHH`, `timeMM`, `mon`, `tue`, `wed`, `thu`, `fri`, `sat`, `sun`, `active`, `nid`, `level`, `isON`) VALUES ('$title', '$execTimeHr', '$execTimeMin', '$mon', '$tue', '$wed', '$thu', '$fri', '$sat', '$sun', '1', '$id', '$level', '$isOn');--";
		mysql_query($updateQuery);
		//echo "Error:$type, $title, $execTimeHr, $execTimeMin, $execTimeAMPM, $daysOfWeek, $id, $isOn, $level";
		//echo "Type: $type\nTitle: $title \nTime: $execTimeHr : $execTimeMin $execTimeAMPM \n$daysOfWeek";
		
	}
	
	public function addScheduleFixedInterval($type, $title, $execTimeHr, $execTimeMin, $date, $intervalDays, $intervalHours, $intervalMinutes, $id, $isOn, $level){
		//`title`, `timeHH`, `timeMM`, `timeAMPM`, `date`, `timeHzDD`, `timeHzHH`, `timeHzMM`, `active`, `nid`, `level`, `isON`
		$valid = true;
		//Check for input errors
		
		$dateArray = explode ( "/" , $date );
		$todayMonth = intval($dateArray[0]);
		$todayDay = intval($dateArray[1]);
		$todayYear = intval($dateArray[2]);
		$date = $todayMonth . "/" . $todayDay . "/" . $todayYear;
		
		
		
		if(count($dateArray) != 3)
			$valid = false;
		else{
			$valid = checkdate( $dateArray[0], $dateArray[1], $dateArray[2] );
		}
		
		
		if($valid){
			$updateQuery = "INSERT INTO `schedulesInterval`(`title`, `timeHH`, `timeMM`, `date`, `timeHzDD`, `timeHzHH`, `timeHzMM`, `active`, `nid`, `level`, `isON`) VALUES ('$title', '$execTimeHr', '$execTimeMin', '$date', '$intervalDays', '$intervalHours', '$intervalMinutes', '1', '$id', '$level', '$isOn');--";
			mysql_query($updateQuery);
			//echo "Error:$type, $title, $execTimeHr, $execTimeMin, $execTimeAMPM, $date, $intervalDays, $intervalHours, $intervalMinutes, $id, $isOn, $level";
		}
		else{
			//echo "Type: $type\nTitle: $title \nTime: $execTimeHr : $execTimeMin $execTimeAMPM \n$date, $intervalDays, $intervalHours, $intervalMinutes";
			echo "Error: Incorrect 'First Execution Date'";
		}
	}
	
}



?>