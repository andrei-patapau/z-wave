<?php
session_start();
if(!isset($_SESSION["myusername"])){
header("location:/Logout.php");//clean session and return to index.php
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script type="text/javascript" src="/js/jquery-1.7.2.js"></script>
<style>
.add_rule td {padding: 0 10px;}
#groups_menu li {text-decoration: none; font-size:13px; font-weight: bold; display:block; padding:5px; background-color:#5B5B66; color: #F2F2F2; margin:10px 0 10px 10px; }
#thenLevelSlider, #ifLevelSliderMin, #ifLevelSliderMax { font-size:50%;margin:10px 0;}
//565051
</style>

<script>
$(function() {
	$( "#groups_menu" ).sortable();
	$( "#groups_menu" ).sortable({ opacity: 0.6 });
	$( "#groups_menu" ).disableSelection();
	$('#groups_menu').sortable({items: '> li:not(.header_li)'});
	$( "#groups_menu" ).sortable({ cancel: '.header_li' });
});
</script>

<script>
$(function() {
	$( "#thenLevelSlider" ).slider({
		value:0, min:0, max:99, step:1,
		slide: function( event, ui ) {
		$('#thenLevelValue').val(ui.value);},
		stop: function( event, ui ) {
		$("#thenLevelSlider").hide();}
	});
});
</script>

<script>
$(function() {
	$( "#ifLevelSliderMin" ).slider({
		value:0, min:0, max:99, step:1,
		slide: function( event, ui ) {
		$('#ifLevelMinValue').val(ui.value);},
		stop: function( event, ui ) {
		$("#ifLevelSliderMin").hide();}
	});
});
</script>

<script>
$(function() {
	$( "#ifLevelSliderMax" ).slider({
		value:0, min:0, max:99, step:1,
		slide: function( event, ui ) {
		$('#ifLevelMaxValue').val(ui.value);},
		stop: function( event, ui ) {
		$("#ifLevelSliderMax").hide();}
	});
});
</script>

<script>

$(document).ready(function() {
	$('#list_of_rules').click(function() {
	
		$(this).next('ul#groups_menu').slideToggle('slow', function() {
		});
		
	});
	
	//$( "ul li#header_li" ).sortable( "option", "disabled", true );
	

	//$('.header_li').sortable('disable'); 
	
	//$("#groups_menu").sortable({ 
	//   items: 'li:not(.ui-state-disabled)' 
	//}); 
	//$('#groups_menu li').sortable('cancel');
	
	//$( "#groups_menu" ).sortable({ cancel: 'header_li' });
});

function add_rule_func()
{
	var if_nid = '-';
	var if_isOn = '-';
	var if_minLevel = '-';
	var if_maxLevel = '-';
	
	var then_nid = '-';
	var then_isOn = '-';
	var then_Level = '-';
	
	if_nid = $('#if_node_name option:selected').attr('id');
	then_nid = $('#then_node_name option:selected').attr('id');
	
	if(!$('#ifLevelMinValue').attr("disabled"))
		if_minLevel = $('#ifLevelMinValue').val();//$('#ifminlevel option:selected').text();//alert($('#ifminlevel').attr("disabled"));
	
	if(!($('#ifLevelMaxValue').attr("disabled")))
		if_maxLevel = $('#ifLevelMaxValue').val();//$('#ifmaxlevel option:selected').text();
		
	if(!($('#if_isON').attr("disabled")))
		if_isOn = $('#if_isON option:selected').text();
		
	if(!$('#then_isON').attr("disabled"))
		then_isOn = $('#then_isON option:selected').text();
		
	if(!$('#thenLevelValue').attr("disabled"))
		then_Level = $('#thenLevelValue').val();
	
	
	//alert(if_nid + "|" + if_isOn + "|" + if_minLevel + "|" + if_maxLevel + "|" + then_nid + "|" + then_isOn + "|" + then_Level);
	
	$.post("functions/changeRules.php", { action: "add_rule", 
	if_nid: if_nid, 
	if_isOn: if_isOn, 
	if_minLevel: if_minLevel, 
	if_maxLevel: if_maxLevel, 
	then_nid: then_nid, 
	then_isOn: then_isOn, 
	then_Level: then_Level},
	
	function(data) {
		//alert(data);
		//location.reload();
		refreshRules();
		//$('#device_content').html(data);
	});

}

$(document).ready(function() {
	if($('#if_node_name').attr('value') == 'Controller'){
	
		$('#ifLevelMinValue').attr("disabled", true);
		$('#ifLevelMaxValue').attr("disabled", true);
		$('#if_isON').attr("disabled", true);
		
		$('#if_type').html('Controller');
	}
	
	if($('#then_node_name').attr('value') == 'Controller'){
		$('#then_isON').attr("disabled", true);
		$('#thenLevelValue').attr("disabled", true);
		
		$('#then_type').html('Controller');
	}
});

$(document).ready(function() {

$('#then_node_name').change(function()
{
	var type = $(this).attr('value');
	//Socket, Light, Controller
	if(type=='Socket'){
		$('#thenLevelValue').attr("disabled", true);
		$('#then_isON').attr("disabled", false);
		$('#then_type').html('Socket');
	}
	else if(type=='Light'){
		$('#thenLevelValue').attr("disabled", false);
		$('#then_isON').attr("disabled", true);
		$('#then_type').html('Light');
	}
	else if(type=='Controller'){
		$('#thenLevelValue').attr("disabled", true);
		$('#then_isON').attr("disabled", true);
		$('#then_type').html('Controller');
	}
});

$('#if_node_name').change(function() 
{
	var type = $(this).attr('value');
	//Socket, Light, Controller
	if(type=='Socket'){
		$('#ifLevelMinValue').attr("disabled", true);
		$('#ifLevelMaxValue').attr("disabled", true);
		$('#if_isON').attr("disabled", false);
		$('#if_type').html('Socket');
	}
	else if(type=='Light'){
		$('#ifLevelMinValue').attr("disabled", false);
		$('#ifLevelMaxValue').attr("disabled", false);
		$('#if_isON').attr("disabled", true);
		$('#if_type').html('Light');
	}
	else if(type=='Controller'){
		$('#ifLevelMinValue').attr("disabled", true);
		$('#ifLevelMaxValue').attr("disabled", true);
		$('#if_isON').attr("disabled", true);
		$('#if_type').html('Controller');
	}
});
	
});
</script>

<script>

$(function() {
	$("#groups_menu").sortable({ 
		stop: function(event, ui) { 
			var itemID = $(ui.item).attr("id"); 
			var items = $("li#" + itemID).parent("#groups_menu").children(); 
			
			for (var i = 1; i <= items.length - 1; i++) { 

				var singleitemID = $(items[i]).attr("id");
				var index = i;
				//alert(singleitemID + " index: " + index);
				//at this point update index based on: singleitemID and index
				//----------------------------------------------------------------
				updateIndex(singleitemID, index);
				//----------------------------------------------------------------
				//alert(index + " | " + items.length + " | ID: " + singleitemID);
				//----------------------------------------------------------------

				var currentId = $('#' + singleitemID + ' input.special').attr('id');
				//alert(currentId);
				$('#' + singleitemID + ' input.special').val(index);

			} 
			
		} 
	}); 
}); 

function updateIndex(id, index)
{	
	$.post("functions/changeRules.php", { action: "updateIndex", id: id, index: index} );
}

function refreshRules()
{	

	$.post("functions/changeRules.php", { action: "refreshRules"},
		function(data) {
			//alert("Data Loaded: " + data);
			$('#groups_menu').html(data);
		});
	
}

$(document).ready(function() {
	refreshRules();
});
</script>
<script>
function remove(id)
{	

	$.post("functions/changeRules.php", { action: "removeRule", id: id},
		function(data) {
			//alert("Data Loaded: " + data);
			//$('#groups_menu').html(data);
			refreshRules();
		});

}


</script>
<?php include("../includes/imports.php"); ?>
<?php require_once '../connection.php'; ?>
<title>BetaHomes</title>
</head>
<body>



<div id="wrapper">
	<div class="container" style="border-style:groove;">
		<?php include("../includes/header.php"); ?>
		<!-- START BODY OF CONTAINER WITHIN CENTER TAG -->
		<div id="divBorder">
			

			<div style="padding:5px;margin-bottom:10px; background-color:#565051; color: #F2F2F2;"><font size="4"><b>Add Rule</b></font></div>

			<div style="padding:5px; background-color:#565051; color: #F2F2F2;">
			
				<table style="text-align:center;" class="add_rule">
					<tr>
						<td style="font-size:70%;">
							If Node Name
						</td>
						<td style="font-size:70%;">
							If is ON
						</td>
						<td style="font-size:70%;">
							Level >
						</td>
						<td style="font-size:70%;">
							Level <
						</td>
						<td style="font-size:70%;">
							
						</td>
						<td style="font-size:70%;">
							Node Name
						</td>
						<td style="font-size:70%;">
							Is ON
						</td>
						<td style="font-size:70%;">
							Level
						</td>
						<td style="font-size:70%;">
							
						</td>
					</tr>
					<tr>
						<td style="padding:0 10px;">
							<!-- if_node_name  ==================================================================-->
							<select style="width:120px;" id="if_node_name">
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
										
										echo "<option id=\"if_n_id_$node_id\" value=\"$type\">$node_name</option>";
									}
								?>
							</select>
						</td>
						<td style="padding:0 10px;">
							<!-- if_isON  ==================================================================-->
							<select style="width:65px;" id="if_isON">
								<option>True</option>
								<option>False</option>
							</select>
						</td>
						<td>
							<!-- ifminlevel  ==================================================================-->
							<!--
							<select style="width:45px;" id="ifminlevel">
								<?php
									for($ifminlevel = 0; $ifminlevel < 100; $ifminlevel = $ifminlevel + 5){
										echo "<option>$ifminlevel</option>";
									}
									echo "<option>99</option>";
								?>
							</select>
							-->
							<input type="text" id="ifLevelMinValue" value="0" style="width:25px;text-align:center"/>
						</td>
						<td>
							<!-- ifmaxlevel  ==================================================================-->
							<!--
							<select style="width:45px;" id="ifmaxlevel">
								<?php
									for($ifmaxlevel = 0; $ifmaxlevel < 100; $ifmaxlevel = $ifmaxlevel + 5){
										echo "<option>$ifmaxlevel</option>";
									}
									echo "<option selected=\"selected\">99</option>";
								?>
							</select>
							-->
							<input type="text" id="ifLevelMaxValue" value="0" style="width:25px;text-align:center"/>
						</td>
						<td>
							===>
						</td>
						<td style="padding:0 10px;">
							<!-- then_node_name  ==================================================================-->
							<select style="width:120px;" id="then_node_name">
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
										
										echo "<option id=\"then_n_id_$node_id\" value=\"$type\">$node_name</option>";
									}
								?>
							</select>
						</td>
						<td style="padding:0 10px;">
							<!-- then_isON  ==================================================================-->
							<select style="width:65px;" id="then_isON">
								<option>True</option>
								<option>False</option>
							</select>
						</td>
						<td>
							<!-- thenLevelValue ==================================================================-->
							<input type="text" id="thenLevelValue" value="0" style="width:25px;text-align:center"/>
						</td>
						<td style="padding:0 10px;">
							<button id="addRulebtn" onClick="add_rule_func()">Add</button>
						</td>
					</tr>
					<tr>
						<td style="font-size:70%;">
							<div id="if_type" style="display:inline;"></div>
						</td>
						<td style="font-size:70%;">
						</td>
						<td style="font-size:70%;">
						</td>
						<td style="font-size:70%;">
						</td>
						<td style="font-size:70%;">
							
						</td>
						<td style="font-size:70%;">
							<div id="then_type" style="display:inline;"></div>
						</td>
						<td style="font-size:70%;">
						</td>
						<td style="font-size:70%;">
						</td>
						<td style="font-size:70%;">
						</td>
					</tr>
				</table>
				<table style="text-align:left;" >
					<tr>
					
						<td width="245px" style="font-size:70%;"></td>
						
						<td style="font-size:70%;">  
							<div id="ifLevelSliderMin" style="width:167px;"></div>
						</td>
						
						<td width="62px" style="font-size:70%;"></td>
						
						<td style="font-size:70%;">  
							<div id="ifLevelSliderMax" style="width:167px;"></div>
						</td>
						
						<td width="357px" style="font-size:70%;"></td>
						
						<td style="font-size:70%;">
							<div id="thenLevelSlider" style="width:167px;"></div>
						</td>

					</tr>
				</table>
			
			</div>
			
			<div style="padding:5px; margin-top:25px; background-color:#565051; color: #F2F2F2; cursor: pointer;" id="list_of_rules"><font size="4"><b>List of rules</b></font></div>
			
			<ul id="groups_menu">

			</ul>
			
			<script>
			//==============================================
			$(document).ready(function() {
				$("#thenLevelSlider").hide();
			});
			$(document).click(function() {
				$("#thenLevelSlider").hide();
			});
			$("#thenLevelValue").click(function (e) {
				$("#ifLevelSliderMin").hide();
				$("#ifLevelSliderMax").hide();
				$('#thenLevelSlider').val($(this).val());
				$("#thenLevelSlider").slideDown(150);
				e.stopPropagation();
			});

			//==============================================
			//Check if Minute is a legal number
			$('#thenLevelValue').blur(function()
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
				$("#ifLevelSliderMin").hide();
			});
			$(document).click(function() {
				$("#ifLevelSliderMin").hide();
			});
			$("#ifLevelMinValue").click(function (e) {
				$("#thenLevelSlider").hide();
				$("#ifLevelSliderMax").hide();
				
				$('#ifLevelSliderMin').val($(this).val());
				$("#ifLevelSliderMin").slideDown(150);
				e.stopPropagation();
			});

			//==============================================
			//Check if Minute is a legal number
			$('#ifLevelMinValue').blur(function()
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
				$("#ifLevelSliderMax").hide();
			});
			$(document).click(function() {
				$("#ifLevelSliderMax").hide();
			});
			$("#ifLevelMaxValue").click(function (e) {
				$("#thenLevelSlider").hide();
				$("#ifLevelSliderMin").hide();
				
				$('#ifLevelSliderMax').val($(this).val());
				$("#ifLevelSliderMax").slideDown(150);
				e.stopPropagation();
			});

			//==============================================
			//Check if Minute is a legal number
			$('#ifLevelMaxValue').blur(function()
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
			</script>
			
		<!-- END BODY OF CONTAINER WITHIN CENTER TAG -->
		</div>
	</div><!-- end .container -->
	<div class="push"></div>
</div><!-- end #wrapper -->
<?php include("../includes/footer.php"); ?>

</body>
</html>
