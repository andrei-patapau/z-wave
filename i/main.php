
<?php
session_start();

if(!isset($_SESSION["myusername"])){
header("location:/Logout.php");//clean session and return to index.php
}
?>


<!DOCTYPE html> 
<html>
<head>
<meta charset="utf-8">
<title>ZWave</title>

<meta name="viewport" content="width=device-width, initial-scale=1">


<link rel="stylesheet"  href="css/themes/default/jquery.mobile-1.1.1.css" />
<script src="js/jquery.js"></script>
<script src="docs/_assets/js/jqm-docs.js"></script>
<script src="js/jquery.mobile-1.1.1.js"></script>
<!--<link href="styles/custom.css" rel="stylesheet" type="text/css">-->
<!--
<script>
$(document).bind("mobileinit", function(){
	$.mobile.ajaxLinksEnabled = false;
});
</script>
-->

</head> 
<body> 

<!-- START of Main Page -->
<div data-role="page" id="page" data-add-back-btn="false">
    <div data-role="header">
    	<h1>Home</h1>
    	<a href="iLogout.php" rel="external" data-icon="delete" class="ui-btn-right">Logout</a>
    </div>

    <div data-role="content">
        <ul data-role="listview">
            <li><a href="lights.php" rel="external"><img src="images/images.jpg" alt="Lights">Lights</a></li>
            <li><a href="outlets.php" rel="external"><img src="images/electrical-outlet-warning.png" width="80" height="80" alt="Outlets">Outlets</a></li>
            
            <li><a href="#rules"><img src="images/rules.jpg" width="80" height="80" alt="Map">Rules</a></li>
            <li><a href="#schedules"><img src="images/schedule.jpg" width="80" height="80" alt="Map">Schedules</a></li>
            
            <!--<li><a href="settings.php" rel="external"><img src="images/settings.png" alt="Settings">Settings</a></li>-->
            <li><a href="#blinds"><img src="images/wooden-window-blinds.jpg" width="80" height="80" alt="Blinds">Blinds</a></li>
            <li><a href="#about"><img src="images/Symbol-Help.png" alt="About">About</a></li>
        </ul>		
    </div>
    
    <div data-role="footer" style="POSITION: relative; BOTTOM: 0px; WIDTH: 100%;">
    	<h4>BetaHomes &copy; 2012</h4>
    </div>
</div>
<!-- END of Main Page -->

<!-- START of rules Page -->
<div data-role="page" id="rules">

	<div data-role="header" >
    	<a data-rel="back" data-icon="arrow-l">Back</a>
		<h1>Rules</h1>
        <a href="iLogout.php" rel="external" data-icon="delete" class="ui-btn-right">Logout</a>
	</div>
    
	<div data-role="content">
    	<img src="images/black-stripes-wallpaper-free-ipod-iphone.jpg" width="325" height="343" alt="Settings" style="margin-left:-20px;margin-top:-20px;">
		<img src="images/apple-construction.png" alt="Settings" style="margin-top:-460px; margin-bottom:37px; margin-left:10px;">
    </div>
	
	<div data-role="footer" style="POSITION: absolute; BOTTOM: 0px; WIDTH: 100%; TEXT-ALIGN: center;">
		<h4>BetaHomes &copy; 2012</h4>
	</div>
</div>
<!-- END of HELP Page -->

<!-- START of schedules Page -->
<div data-role="page" id="schedules">

	<div data-role="header" >
    	<a data-rel="back" data-icon="arrow-l">Back</a>
		<h1>Schedules</h1>
        <a href="iLogout.php" rel="external" data-icon="delete" class="ui-btn-right">Logout</a>
	</div>
    
	<div data-role="content">
    	<img src="images/black-stripes-wallpaper-free-ipod-iphone.jpg" width="325" height="343" alt="Settings" style="margin-left:-20px;margin-top:-20px;">
		<img src="images/apple-construction.png" alt="Settings" style="margin-top:-460px; margin-bottom:37px; margin-left:10px;">
    </div>
	
	<div data-role="footer" style="POSITION: absolute; BOTTOM: 0px; WIDTH: 100%; TEXT-ALIGN: center;">
		<h4>BetaHomes &copy; 2012</h4>
	</div>
</div>
<!-- END of HELP Page -->

<!-- START of Blinds Page -->
<div data-role="page" id="blinds">

	<div data-role="header">
    	<a data-rel="back" data-icon="arrow-l">Back</a>
		<h1>Blinds</h1>
        <a href="iLogout.php" rel="external" data-icon="delete" class="ui-btn-right">Logout</a>
	</div>
    
	<div data-role="content" style="padding-top:0;">
    	<img src="images/blindsProgress.png" width="300" height="350" alt="Settings">
	</div>
    
	<div data-role="footer" style="POSITION: absolute; BOTTOM: 0px; WIDTH: 100%; TEXT-ALIGN: center;">
		<h4>BetaHomes &copy; 2012</h4>
	</div>
    
</div>
<!-- END of Blinds Page -->

<!-- START of HELP Page -->
<div data-role="page" id="about">

	<div data-role="header" >
    	<a data-rel="back" data-icon="arrow-l">Back</a>
		<h1>About</h1>
        <a href="iLogout.php" rel="external" data-icon="delete" class="ui-btn-right">Logout</a>
	</div>
    
	<div data-role="content" style="padding-top:0; padding-bottom:28px;margin-top:15px;">
    	<ul data-role="listview" data-inset="true">
        	<li>
		Z-Wave Smart Home - Open Smart Home Automation Project for Linux<br><br>
        
        This project is a free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.
        <br><br>
Copyright (C) 2012 Team Betahomes<br>University of Texas at Arlington
        	</li>
        </ul>	
	</div>
    
	<div data-role="footer" style="POSITION: absolute; BOTTOM: 0px; WIDTH: 100%; TEXT-ALIGN: center;">
		<h4>BetaHomes &copy; 2012</h4>
	</div>
</div>
<!-- END of HELP Page -->


</body>
</html>