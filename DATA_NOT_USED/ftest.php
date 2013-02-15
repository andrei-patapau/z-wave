<!------------------------------------------STAGE_1-once----------------------------------------------------->
<!DOCTYPE html>
<html>
<head>
<link href="js/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script src="js/jquery-ui.min.js"></script>

<style>
b.blink-cursor {
width: 10px;
height: 27px;
background: url(ve-blink-cursor.png) no-repeat;
}
</style>
<script>
$(document).ready(function() {
$("button").button();

});
</script>
<!------------------------------------------SCRIPTS_STAGE_2-multiple----------------------------------------------------->
<script type="text/javascript">
$(function() {
	$( "#sliderXXX" ).slider({
		value:0,
		min: 0,
		max: 99,
		step: 1,
		stop: function( event, ui ) {
			showValue_XXX_(ui.value);
		}
	});
	showValue_XXX_(ui.value);
});

function showValue_XXX_(newValue){document.getElementById("range_XXX").innerHTML=newValue;var a = "range_XXX_";var c = a.concat(newValue);frameExecute(c);}

function clickButton_XXX_(id){frameExecute(id);}
</script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.3/jquery.min.js"></script>   
<script>

function birdShow(){

    birdFly='<table cellPadding=0 cellspacing=0 width="100%" height="100%"><tr><td align=right><img src="close.png" width=35 height=34 border=0 onClick="birdStop();"><tr><td height="99%"><object classid=clsid:D27CDB6E-AE6D-11cf-96B8-444553540000 codebase=http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=4,0,2,0 width=100% height=100%><param name=movie value=bird.swf><param name=quality value=high><embed src=bird.swf quality=high pluginspage=http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash type=application/x-shockwave-flash width=100% height=100%></embed></object></table>';

    $('#birdDiv').css('visibility','visible'); $('#birdDiv').css('left','0px'); $('#birdDiv').css('top','0px'); $('#birdDiv').html(birdFly); birdShowSize(); $(window).resize(function() {birdShowSize();});



}



function birdShowSize(){ $('#birdDiv').css('width', $(window).width() - 30); $('#birdDiv').css('height',$(window).height() - 30); }



function birdStop(){

 $('#birdDiv').css('width', 1); $('#birdDiv').css('height',1); $('#birdDiv').css('visibility','hidden'); $('#birdDiv').css('left','-1000px'); $('#birdDiv').css('top','-1000px'); $('#birdDiv').html(""); 



}

</script> 
<!-----------------------------------STAGE_3-once------------------------------------------------------------>
<script>

onload=function() {
birdShow();
}

</script>
<script>//var i = 0;return i;</script>
</head>
<body>

<!--------------------------------------FORMS_STAGE_4-multiple--------------------------------------------------------->
<!--
<fieldset class="ui-corner-all" style="width:170px; background-color:#FAFAFA; display:inline; text-align:center;">
<legend>Node n</legend>
<img src="js/lightBulbGrey.png" width="76" height="86">

<table width="85px" height="100px">
<col />
	<tr valign="baseline" >
		<td style="text-align:center;border-style:none;font-size:100%;">
			<br>
			<button id="button_XXX_on" onClick="clickButton_XXX_(this.id)">ON</button>
			<br><br>
			<button id="button_XXX_off" onClick="clickButton_XXX_(this.id)">OFF</button>
		</td>
	</tr>
</table>
<br>
<div id="sliderXXX" style="font-size:50%;" ></div>
<div style="margin-top:5px"><span id="range_XXX" style="border:0; color:#606060; font-weight:bold;">0</span></div>
</fieldset>
<!-----------------------------------STAGE_5-once------------------------------------------------------------->
<button id="bird" onClick="birdShow()">Bird</button>



<!--<b style="background: url(ve-blink-cursor.png) no-repeat;width: 10px; height: 27px;">&nbsp;</b>-->
<img style="opacity:1" src="ve-blink-cursor.png" alt="Smiley face" height="27" width="10" />
<div id="birdDiv" ></div>
</body>
</html>
