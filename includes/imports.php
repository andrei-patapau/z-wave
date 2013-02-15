
<?php
$data = <<<ETF
<meta charset="utf-8">
<link rel="stylesheet" href="/css/mainStyle.css" type="text/css" media="all">
<link href="/js/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script src="/js/jquery.min.js"></script>
<script src="/js/jquery-ui.min.js"></script>

<script>

function about()
{
window.location = "/pages/about.php";
}

function logout()
{	
	window.location = "/Logout.php";
}

function showBird()
{
window.location = "/bird.php";
}

function checkLogin()
{	
	var uname = $('#myusername').val();
	var pass = $('#mypassword').val();
	
	$.post("/checklogin.php", { action: "checkLogin", myusername: uname, mypassword: pass},
		function(data) {
			//alert("Data Loaded: " + data);
			
			if(data.indexOf("Failed") > 0){
				$('#status').html(data);
			}
			else{
				window.location = "/main.php";
			}
		});
	
}

$(document).ready(function() {
$("button").button();
});

function birdShow(){

    birdFly='<table cellPadding=0 cellspacing=0 width="100%" height="100%"><tr><td align=right><img src="images/close.png" width=35 height=34 border=0 onClick="birdStop();"><tr><td height="99%"><object classid=clsid:D27CDB6E-AE6D-11cf-96B8-444553540000 codebase=http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=4,0,2,0 width=100% height=100%><param name=movie value=/js/bird.swf><param name=quality value=high><embed src=/js/bird.swf quality=high pluginspage=http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash type=application/x-shockwave-flash width=100% height=100%></embed></object></table>';

    $('#birdDiv').css('visibility','visible'); $('#birdDiv').css('left','0px'); $('#birdDiv').css('top','0px'); $('#birdDiv').html(birdFly); birdShowSize(); $(window).resize(function() {birdShowSize();});
}
function birdShowSize(){ $('#birdDiv').css('width', $(window).width()); $('#birdDiv').css('height',$(window).height()); }
function birdStop(){

 $('#birdDiv').css('width', 1); $('#birdDiv').css('height',1); $('#birdDiv').css('visibility','hidden'); $('#birdDiv').css('left','-1000px'); $('#birdDiv').css('top','-1000px'); $('#birdDiv').html("");
 window.location = "/main.php";
}
</script>
ETF;
echo $data;
?>