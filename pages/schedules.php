<?php
session_start();
if(!isset($_SESSION["myusername"])){
header("location:/Logout.php");//clean session and return to index.php
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<style type="text/css">
	#sliderMin, #sliderHr, #intervalDaysSlider, #intervalHoursSlider, #intervalMinutesSlider, #sliderNodeLevel { font-size:50%;margin:10px 0;}
	.add_schedule {padding: 0 10px;text-align:left;}
	#schedules_menu li {text-decoration: none; text-align:left; font-size:13px; font-weight: bold; display:block; padding:5px 5px 5px 15px; background-color:#5B5B66; color: #000000; margin:10px 0 10px 10px; }
</style>
<script src="/js/jquery.min.js"></script>
<script type="text/javascript" src="/js/jquery-ui.min.js"></script>
<!-- Date Picker files------------------>
<script>
$(function() {
	$( ".datepicker" ).datepicker({
		numberOfMonths: 3,
		showButtonPanel: true
	});
});
</script>
<!-- Date Picker files------------------>
<script>
//sliderNodeLevel

$(function() {
	$( "#sliderNodeLevel" ).slider({
		value:0, min:0, max:99, step:1,
		slide: function( event, ui ) {
		$('#level').val(ui.value);},
		stop: function( event, ui ) {
		$("#sliderNodeLevel").hide();}
	});
});
</script>

<script>
$(function() {
	$( "#intervalMinutesSlider" ).slider({
		value:0, min:0, max:59, step:1,
		slide: function( event, ui ) {
		$('#intervalMinutes').val(ui.value);},
		stop: function( event, ui ) {
		$("#intervalMinutesSlider").hide();}
	});
});
</script>

<script>
$(function() {
	$( "#intervalHoursSlider" ).slider({
		value:0, min:0, max:24, step:1,
		slide: function( event, ui ) {
		$('#intervalHours').val(ui.value);},
		stop: function( event, ui ) {
		$("#intervalHoursSlider").hide();}
	});
});
</script>

<script>
$(function() {
	$( "#intervalDaysSlider" ).slider({
		value:0, min:0, max:31, step:1,
		slide: function( event, ui ) {
		$('#intervalDays').val(ui.value);},
		stop: function( event, ui ) {
		$("#intervalDaysSlider").hide();}
	});
});
</script>

<script>
$(function() {
	$( "#sliderHr" ).slider({
		value:0, min:0, max:23, step:1,
		slide: function( event, ui ) {
		$('#selectHour').val(ui.value);},
		stop: function( event, ui ) {
		$("#sliderHr").hide();}
	});
});
</script>

<script>
$(function() {
	$( "#sliderMin" ).slider({
		value:0, min:0, max:59, step:1,
		slide: function( event, ui ) {
		$('#selectMinute').val(ui.value);},
		stop: function( event, ui ) {
		$("#sliderMin").hide();}
	});
});
</script>

<script>
$(document).ready(function() {
	$('#ifOnce').show();
	$('#ifEveryDay').hide();
	$('#ifWeekly').hide();
	$('#ifFixedInterval').hide();
});

$(document).ready(function() {

	$('#frequency').change(function() 
	{
		var type = $('#frequency option:selected').attr('id');
		//alert(type);
		
		if(type=='once'){
			$('#ifOnce').show();
			$('#ifEveryDay').hide();
			$('#ifWeekly').hide();
			$('#ifFixedInterval').hide();
		}
		else if(type=='everyDay'){
			$('#ifOnce').hide();
			$('#ifEveryDay').show();
			$('#ifWeekly').hide();
			$('#ifFixedInterval').hide();
		}
		else if(type=='weekly'){
			$('#ifOnce').hide();
			$('#ifEveryDay').hide();
			$('#ifWeekly').show();
			$('#ifFixedInterval').hide();
		}
		else if(type=='fixedInterval'){
			$('#ifOnce').hide();
			$('#ifEveryDay').hide();
			$('#ifWeekly').hide();
			$('#ifFixedInterval').show();
		}
	});
});

$(document).ready(function() {
	$('#setSchedule').click(function() {
	
	
		//get type of schedule
		var type = $('#frequency option:selected').attr('id');
		var title = $('#scheduleTitle').val();
		
		//Get Node Info----------------------------------------------
		var isOn = '-';
		var level = '-';
		
		if(!$('#isON').attr("disabled"))
			isOn = $('#isON option:selected').text();
		
		if(!$('#level').attr("disabled"))
			level = $('#level').val();
			
		var id = $('#node_name option:selected').attr('id');
		id = id.replace("n_id_","");
		
		//alert(isOn + "|" + level + "|" + id);
		//Get Node Info----------------------------------------------
		
		var addSchedule = 1;

		if(type=='once'){
			
			var execTimeHr = $('#selectHour').val();
			var execTimeMin = $('#selectMinute').val();
			var date = $('#ifOnceDate').val();
			var deleteOnCompletionBox = $('#ifOnceDelOnCompletion').is(':checked');
			//alert(title + execTimeHr + " : " + execTimeMin + " " + execTimeAMPM + "\n" + date + " delete:? " + deleteOnCompletionBox);
			/*
			var currDateArray = date.split("/");
			var currDateMM = currDateArray[0];
			var currDateDD = currDateArray[1];
			var currDateYY = currDateArray[2];
			
			var nowDate = new Date();
			var nowMM = nowDate.getDate();
			var nowDD = nowDate.getMonth();
			var nowYY = nowDate.getFullYear();
			
			
			alert(currDateMM + '/' + currDateDD + '/' + currDateYY + "-" + nowMM + '/' + nowDD + '/' + nowYY);
			*/
			/*
			if((currDate < d) || (isNaN(currDate)==true)){
				alert('Error: Invalid Date' + currDate + d);
				addSchedule = 0;
			}
			else
				alert('success');
			*/
			var schedulesData = new Array(type, title, execTimeHr, execTimeMin, date, deleteOnCompletionBox, id, isOn, level);
		}
		else if(type=='everyDay'){
			var execTimeHr = $('#selectHour').val();
			var execTimeMin = $('#selectMinute').val();
			//alert(execTimeHr + " : " + execTimeMin + " " + execTimeAMPM);
			
			var schedulesData = new Array(type, title, execTimeHr, execTimeMin, id, isOn, level);
		}
		else if(type=='weekly'){
			var execTimeHr = $('#selectHour').val();
			var execTimeMin = $('#selectMinute').val();
			
			
			var mon = $('#mon').is(':checked');
			var tue = $('#tue').is(':checked');
			var wed = $('#wed').is(':checked');
			var thu = $('#thu').is(':checked');
			var fri = $('#fri').is(':checked');
			var sat = $('#sat').is(':checked');
			var sun = $('#sun').is(':checked');
			
			var weekDays = new Array(mon, tue, wed, thu, fri, sat, sun);
			//alert(execTimeHr + " : " + execTimeMin + " " + execTimeAMPM + "\n" + weekDays);
			
			var schedulesData = new Array(type, title, execTimeHr, execTimeMin, weekDays, id, isOn, level);
		}
		else if(type=='fixedInterval'){
			var execTimeHr = $('#selectHour').val();
			var execTimeMin = $('#selectMinute').val();
			var date = $('#ifFixedIntervalDate').val();
			
			var intervalDays = $('#intervalDays').val();
			var intervalHours = $('#intervalHours').val();
			var intervalMinutes = $('#intervalMinutes').val();
			
			//alert(execTimeHr + " : " + execTimeMin + " " + execTimeAMPM + "\n" + date + "\n" + intervalDays + "days " + intervalHours + "h" + intervalMinutes + "m");
			
			var schedulesData = new Array(type, title, execTimeHr, execTimeMin, date, intervalDays, intervalHours, intervalMinutes, id, isOn, level);
		}
		
		//CONDITIONS/ALERTS - WHY CAN'T THE SCHEDULE BE ADDED!
		//FOR 'Fixed Interval' Can't be: 1) 'date' != 0	2) DD && HH && MM != 0
		//FOR 'Weekly' Can't be: 1) week days fields all 'false' (none of them checked)
		//FOR 'Every Day' Can't be: 1) NONE (any input is OK)
		//FOR 'Once' Can't be: 1) 'date' != 0
		//FOR Execution Time(General Case) : Current Time > Execution Time
		
		//var d = new Date(year, month, day, hours, minutes, seconds, milliseconds);
		//var d = new Date();
		//alert(d.getHours() + ":" + d.getMinutes());
		
		
		$.post("functions/changeSchedules.php", { action: "addSchedule", schedulesData: schedulesData},
			function(data) {
				reloadSchedules();
				if(data.indexOf("Error") != -1)
					alert(data);
		});
		
	});
	
	$('#resetForm').click(function() {
		
		//reset Title
		$('#scheduleTitle').val('');
		
		//set Hour / Min values to 0
		$('#selectHour').val('0');
		$('#selectMinute').val('0');
		$('#level').val('0');
		
		//set sliders to 0
		$('#sliderMin').slider( "value" , 0);
		$('#sliderHr').slider( "value" , 0);
		$('#sliderNodeLevel').slider( "value" , 0);
		
		//FOR 'ONCE'
		$('#ifOnceDate').val('');
		$('#ifOnceDelOnCompletion').attr('checked', false);

		//FOR 'EVERY DAY'
		//Do Nothing
		
		//FOR 'WEEKLY'
		$('#mon').attr('checked', false);
		$('#tue').attr('checked', false);
		$('#wed').attr('checked', false);
		$('#thu').attr('checked', false);
		$('#fri').attr('checked', false);
		$('#sat').attr('checked', false);
		$('#sun').attr('checked', false);
		
		//FOR 'FIXED INTERVAL'
		$('#ifFixedIntervalDate').val('');
		$('#intervalDays').val('0');
		$('#intervalHours').val('0');
		$('#intervalMinutes').val('0');
		$('#intervalDaysSlider').slider( "value" , 0);
		$('#intervalHoursSlider').slider( "value" , 0);
		$('#intervalMinutesSlider').slider( "value" , 0);
		
		
		
	});
	
	

});

//load schedules on page load
$(document).ready(function() {
	reloadSchedules();
});

$(document).ready(function() {
	$('#list_of_schedules').click(function() {
		$(this).next('ul#schedules_menu').slideToggle('slow', function() {
		});
	});
});

//--- Disable unused elements [In Select Node]------------------
$(document).ready(function() {	
	if($('#node_name').attr('value') == 'Controller'){
		var id = $('#node_name option:selected').attr('id');
		id = id.replace("n_id_","");
		$('#isON').attr("disabled", true);
		$('#level').attr("disabled", true);
		$('#nodeInfo').val('Type: Controller; Node ID: ' + id);
	}
	
	$('#node_name').change(function() 
	{
		var type = $(this).attr('value');
		var id = $('#node_name option:selected').attr('id');
		id = id.replace("n_id_","");
		
		//Socket, Light, Controller
		if(type=='Socket'){
			$('#level').attr("disabled", true);
			$('#isON').attr("disabled", false);
			$('#nodeInfo').val('Type: Socket; Node ID: ' + id);
		}
		else if(type=='Light'){
			$('#level').attr("disabled", false);
			$('#isON').attr("disabled", true);
			$('#nodeInfo').val('Type: Light; Node ID: ' + id);
		}
		else if(type=='Controller'){
			$('#level').attr("disabled", true);
			$('#isON').attr("disabled", true);
			$('#nodeInfo').val('Type: Controller; Node ID: ' + id );
		}
	});
//--------------------------------------------------------------
});

function reloadSchedules(){

	$.post("functions/changeSchedules.php", { action: "refreshSchedules"},
		function(data) {
			//alert("Data Loaded: " + data);
			
			$('#schedules_menu').html(data);
		});


}

function activeFalse(idBox){
	
	var checkedStatus = $('#' + idBox).attr('checked');
	//alert(idBox + checkedStatus);
	
	var schedulesActive = new Array(idBox, checkedStatus);
	
	$.post("functions/changeSchedules.php", { action: "activeOrNot", schedulesActive: schedulesActive},
		function(data) {
			//alert(data);
			//$('#schedules_menu').html(data);
		});

}

function removeSchedule(deleteSchedule){
		
	$.post("functions/changeSchedules.php", { action: "deleteSchedule", deleteSchedule: deleteSchedule},
		function(data) {
			//alert(data);
			reloadSchedules();
			//$('#schedules_menu').html(data);
		});

}



</script>

<?php include("../includes/imports.php"); ?>
<?php require_once '../connection.php'; ?>

<style>
.leftCol {width: 200px; text-align: right; font-weight:bold; font-family:"Cambria (Headings)"; padding-right:10px;}
.leftColNode {width: 90px; text-align: right; font-weight:bold; font-family:"Cambria (Headings)"; padding-right:10px;}
.rightCol {text-align: left; }
//#mainTable tr {line-height:35px;}
</style>
<title>BetaHomes</title>
</head>
<body>
<div id="wrapper">
	<div class="container" style="border-style:groove;">
		<?php include("../includes/header.php"); ?>
		<center>
		<!-- START BODY OF CONTAINER WITHIN CENTER TAG -->
			<div id="divBorder">
				
				<fieldset class="ui-corner-all" style="width:800px;padding:30px 0;">
					<!--<button onclick="reloadSchedules()"> Reload </button>-->
					<div style="width:600px;margin-bottom:10px;">
						<table id="mainTable" width="600px">
							
							<tr>
								<td class="leftCol">
									Schedule Title:
								</td>
								<td class="rightCol">
									<input type="text" type="submit" id="scheduleTitle" style="width:300px;text-align:left">
								</td>
							</tr>
						</table>
						
						<fieldset class="ui-corner-all" style="width:460px;margin:20px 0;padding:10px 0;text-align:center;">
							<table width="380px" style="margin-left:37px;">
								<tr>
									<td>
										
									</td>
									<td class="rightCol" style="font-size:70%;">
										Node Name
									</td>
									<td style="padding:0 10px;font-size:70%;">
										On / Off
									</td>
									<td style="font-size:70%;">
										Level
									</td>
								</tr>
								<tr>
									<td style="text-align: right; font-weight:bold;padding-right:10px;font-family:Cambria;">
										Select Node:
									</td>
									<td class="rightCol">
										<select style="width:130px;" id="node_name">
											<?php
												//-------------------------------------------------------------------------------------------------------
												//Check if name exist	
												$nodes = mysql_query("SELECT * FROM nodes");
												
												while($node_id = mysql_fetch_array($nodes)){
												
													$node_id = $node_id['node_id'];
													
													//NAME---------------------------------------------------------------------------------
													$node_names = mysql_query("SELECT name FROM node_names WHERE node_id = '$node_id'");
													if($node_name = mysql_fetch_array($node_names)){
														$node_name = $node_name['name'];
													}
													else{
														$node_name = "Name Not Defined";
													}
													//NAME---------------------------------------------------------------------------------
													
													//TYPE----------------------------
													$type = "Type Not Found";
													$commandClasses = mysql_query("SELECT * FROM command_classes WHERE node_id = '$node_id'");
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
													
													echo "<option id=\"n_id_$node_id\" value=\"$type\">$node_name</option>";
												}
											?>
										</select>
									</td>
									<td style="padding:0 10px;">
										<!-- then_isON  ==================================================================-->
										<select style="width:60px;" id="isON">
											<option>On</option>
											<option>Off</option>
										</select>
									</td>
									<td>
										<input type="text" id="level" value="0" style="width:25px;text-align:center;"/>
									</td>
								</tr>

							</table>
							<input type="text" id="nodeInfo" value="" style="width:360px;text-align:center; margin-top:7px;" readonly/>
							<div id="sliderNodeLevel" style="width:413px;margin-top:15px;margin-left:24px;"></div>
							
						</fieldset>
					</div>
					<hr width="600px" size="5"/>
					<table id="mainTable" width="600px" style="margin-top:10px;">
						
						<tr>
							<td class="leftCol">
								Frequency:
							</td>
							<td class="rightCol">
								<select style="width:200px;" id="frequency">
									<option id="once">Once</option>
									<option id="everyDay">Every Day</option>
									<option id="weekly">Weekly</option>
									<option id="fixedInterval">Fixed Interval</option>
								</select>
							</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td class="leftCol">
								Execution Time:
							</td>
							<td class="rightCol">
								<input type="text" id="selectHour" value="0" style="width:30px;text-align:center"/><span style="font-size:70%;">hr</span> :
								<input type="text" id="selectMinute" value="0" style="width:25px;text-align:center"/><span style="font-size:70%;">min</span>
							</td>
						</tr>
						<tr>
							<td class="leftCol">
								
							</td>
							
							<td class="rightCol">
								<div id="sliderMin" style="width:167px;"></div>
								<div id="sliderHr" style="width:167px;"></div>
							</td>
						</tr>
					</table>
					<br>
					
					<div id="ifOnce">
						<table width="600px">
							<tr>
								<td class="leftCol">
									Date:
								</td>
								<td class="rightCol">
									<input type="text" id="ifOnceDate" class="datepicker" style="width:90px;text-align:center">
								</td>
							</tr>
							<tr style="line-height:35px;">
								<td class="leftCol">

								</td>
								<td class="rightCol">
									<input type="checkbox" id="ifOnceDelOnCompletion" /><span style="font-size:70%;">&nbsp;Delete the schedule on completion</span>
								</td>
							</tr>
						</table>
					</div>
					
					<div id="ifEveryDay">
						<table width="600px">
							<tr>
								<td class="leftCol">
									Date:
								</td>
								<td class="rightCol">
									every day
								</td>
							</tr>
						</table>
					</div>
					
					<div id="ifWeekly">
						<table width="600px">
							<tr>
								<td class="leftCol">
									Week Days:
								</td>
								<td class="rightCol">
									<table>
										<tr>
											<td><input type="checkbox" id="mon" /><span style="font-size:70%;">&nbsp;Mon&nbsp;&nbsp;</span></td>
											<td><input type="checkbox" id="tue" /><span style="font-size:70%;">&nbsp;Tue&nbsp;&nbsp;</span></td>
											<td><input type="checkbox" id="wed" /><span style="font-size:70%;">&nbsp;Wed&nbsp;&nbsp;</span></td>
											<td><input type="checkbox" id="thu" /><span style="font-size:70%;">&nbsp;Thu&nbsp;&nbsp;</span></td>
										</tr>
										<tr>
											<td><input type="checkbox" id="fri" /><span style="font-size:70%;">&nbsp;Fri&nbsp;&nbsp;</span></td>
											<td><input type="checkbox" id="sat" /><span style="font-size:70%;">&nbsp;Sat&nbsp;&nbsp;</span></td>
											<td><input type="checkbox" id="sun" /><span style="font-size:70%;">&nbsp;Sun&nbsp;&nbsp;</span></td>
											<td></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</div>
					
					<div id="ifFixedInterval">
						<table width="600px">
							<tr>
								<td class="leftCol">
									First Execution Date:
								</td>
								<td class="rightCol">
									<input type="text" id="ifFixedIntervalDate" class="datepicker" style="width:90px;text-align:center">
								</td>
							</tr>
							<tr style="line-height:35px;">
								<td class="leftCol">
									Interval:
								</td>
								<td class="rightCol">
									<input type="text" id="intervalDays" value="0" style="width:30px;text-align:center"/><span style="font-size:70%;">day(s)</span>
									<input type="text" id="intervalHours" value="0" style="width:25px;text-align:center"/><span style="font-size:70%;">hh</span> :
									<input type="text" id="intervalMinutes" value="0" style="width:25px;text-align:center"/><span style="font-size:70%;">mm</span>
								</td>
							</tr>
							<!-------------------------->
							<tr>
								<td class="leftCol">
									
								</td>
								
								<td class="rightCol">
									<div id="intervalDaysSlider" style="width:167px;"></div>
									<div id="intervalHoursSlider" style="width:167px;"></div>
									<div id="intervalMinutesSlider" style="width:167px;"></div>
								</td>
							</tr>
							<!-------------------------->
						</table>
					</div>
					
					<div align="right" style="width:600px;"><br><hr size="5"/><br>
						<button id="resetForm" style="width:60px;">Reset</button>&nbsp;&nbsp;&nbsp;&nbsp;<button id="setSchedule" style="width:60px;">Add</button>
					</div>
					
				</fieldset>
				
				
				<div style="padding:5px; margin-top:25px; background-color:#565051; color: #F2F2F2; cursor: pointer;" id="list_of_schedules"><font size="4"><b>List of schedules</b></font></div>
				
				<ul id="schedules_menu">
					<!-- LIST OF SCHEDULES GOES HERE -->
				</ul>
				
				
<script>
//==============================================
$(document).ready(function() {
	$("#sliderNodeLevel").hide();
});
$(document).click(function() {
	$("#sliderNodeLevel").hide();
});
$("#level").click(function (e) {
	$("#sliderMin").hide();
	$("#sliderHr").hide();
	$("#intervalDaysSlider").hide();
	$("#intervalHoursSlider").hide();
	$("#intervalMinutesSlider").hide();
	$('#sliderNodeLevel').val($(this).val());
	$("#sliderNodeLevel").slideDown(150);
	e.stopPropagation();
});

//==============================================
//Check if Minute is a legal number
$('#level').blur(function()
{
    if( $(this).val() > 99 ) {
        $(this).val(99);
    }
	if( $(this).val() < 0 ) {
        $(this).val(0);
    }
	if( isNaN($(this).val())) {
        $(this).val(0);
    }
});
//==============================================

//==============================================
$(document).ready(function() {
	$("#intervalMinutesSlider").hide();
});
$(document).click(function() {
	$("#intervalMinutesSlider").hide();
});
$("#intervalMinutes").click(function (e) {
	$("#intervalDaysSlider").hide();
	$("#intervalHoursSlider").hide();
	$("#sliderNodeLevel").hide();
	$('#intervalMinutesSlider').val($(this).val());
	$("#intervalMinutesSlider").slideDown(150);
	e.stopPropagation();
});

//==============================================
//Check if Minute is a legal number
$('#intervalMinutes').blur(function()
{
    if( $(this).val() > 59 ) {
        $(this).val(59);
    }
	if( $(this).val() < 0 ) {
        $(this).val(0);
    }
	if( isNaN($(this).val())) {
        $(this).val(0);
    }
});
//==============================================
$(document).ready(function() {
	$("#intervalHoursSlider").hide();
});
$(document).click(function() {
	$("#intervalHoursSlider").hide();
});
$("#intervalHours").click(function (e) {
	$("#intervalDaysSlider").hide();
	$("#intervalMinutesSlider").hide();
	$("#sliderNodeLevel").hide();
	$('#intervalHoursSlider').val($(this).val());
	$("#intervalHoursSlider").slideDown(150);
	e.stopPropagation();
});
//==============================================
//Check if Hour is a legal number
$('#intervalHours').blur(function()
{
    if( $(this).val() > 24 ) {
        $(this).val(24);
    }
	if( $(this).val() < 0 ) {
        $(this).val(0);
    }
	if( isNaN($(this).val())) {
        $(this).val(0);
    }
});
//==============================================

$(document).ready(function() {
	$("#intervalDaysSlider").hide();
});
$(document).click(function() {
	$("#intervalDaysSlider").hide();
});
$("#intervalDays").click(function (e) {
	$("#intervalHoursSlider").hide();
	$("#intervalMinutesSlider").hide();
	$("#sliderNodeLevel").hide();
	$('#intervalDaysSlider').val($(this).val());
	$("#intervalDaysSlider").slideDown(150);
	e.stopPropagation();
});
//==============================================
//Check if Hour is a legal number
$('#intervalDays').blur(function()
{
    if( $(this).val() > 31 ) {
        $(this).val(31);
    }
	if( $(this).val() < 0 ) {
        $(this).val(0);
    }
	if( isNaN($(this).val())) {
        $(this).val(0);
    }
});
//==============================================
</script>



<script>
//==============================================
$(document).ready(function() {
	$("#sliderHr").hide();
});
$(document).click(function() {
	$("#sliderHr").hide();
});
$("#selectHour").click(function (e) {
	$("#sliderMin").hide();
	$("#intervalHoursSlider").hide();
	$("#intervalMinutesSlider").hide();
	$("#sliderNodeLevel").hide();
	$('#sliderHr').val($(this).val());
	$("#sliderHr").slideDown(150);
	e.stopPropagation();
});
//==============================================
//Check if Hour is a legal number
$('#selectHour').blur(function()
{
    if( $(this).val() > 23 ) {
        $(this).val(23);
    }
	if( $(this).val() < 0 ) {
        $(this).val(0);
    }
	if( isNaN($(this).val())) {
        $(this).val(0);
    }
});
//==============================================
$(document).ready(function() {
	$("#sliderMin").hide();
});
$(document).click(function() {
	$("#sliderMin").hide();
});
$("#selectMinute").click(function (e) {
	$("#sliderHr").hide();
	$("#intervalHoursSlider").hide();
	$("#intervalMinutesSlider").hide();
	$("#sliderNodeLevel").hide();
	$('#sliderMin').val($(this).val());
	$("#sliderMin").slideDown(150);
	e.stopPropagation();
});

//==============================================
//Check if Minute is a legal number
$('#selectMinute').blur(function()
{
    if( $(this).val() > 59 ) {
        $(this).val(59);
    }
	if( $(this).val() < 0 ) {
        $(this).val(0);
    }
	if( isNaN($(this).val())) {
        $(this).val(0);
    }
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
