<!------------------------------------------STAGE_1-once----------------------------------------------------->
<!DOCTYPE html>
<html>
<head>
<link href="js/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script src="js/jquery.min.js"></script>
<script src="js/jquery-ui.min.js"></script>



<script>
$(document).ready(function() {
$("button").button();
});

function frameExecute(id)
{	
	$.post("data.php", { action: "frameExecute", command: id},
		function(data) {
			$('#container2').html(data);
		});
}

</script>


		<fieldset class="ui-corner-all" style="width:170px; background-color:#FAFAFA; display:inline; text-align:center;">
		<legend>Node n</legend>
		
		<img src="js/lightBulbGrey.png" width="76" height="86">
		
		<table width="85px" height="100px"><tr valign="baseline" >
		
		<td style="text-align:center;border-style:none;font-size:100%;">
		
		<br><button id="button_3_on" onClick="clickButton_9_(this.id)">ON</button>
		
		<br><br><button id="button_3_off" onClick="clickButton_9_(this.id)">OFF</button>
		
		</td></tr></table><br><div id="slider" style="font-size:50%;" ></div>
		
		<div style="margin-top:5px"><span id="range_9" style="border:0; color:#606060; font-weight:bold;">0</span></div>
		
		</fieldset>
		
		<script type="text/javascript">
		
		$(function() {$( "#slider9" ).slider({value:0,min:0,max:99,step:1,stop: function( event, ui ) {showValue_9_(ui.value);}});showValue_9_(ui.value);});
		
		function showValue_9_(newValue){document.getElementById("range_9").innerHTML=newValue;var a = "range_9_";var c = a.concat(newValue);frameExecute(c);}
		
		function clickButton_9_(id){frameExecute(id);}
		</script>
</body>
</html>
