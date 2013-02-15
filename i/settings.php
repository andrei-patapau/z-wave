<!DOCTYPE html> 
<html>
<head>
<meta charset="utf-8">
<title>ZWave</title>
<link href="jquery-mobile/jquery.mobile-1.0a3.min.css" rel="stylesheet" type="text/css"/>
<script src="jquery-mobile/jquery-1.5.min.js" type="text/javascript"></script>

<script type="text/javascript">
	$(document).bind("mobileinit", function(){
		$.mobile.page.prototype.options.addBackBtn = true;	
	});
</script>

<?php require_once("sample.php"); ?>

<script>

function loadxml()
{
	$.post("script.php", { action: "test"},
   function(data) {
     alert("Data Loaded: " + data);
   });
}

function loadxml2()
{
	$.post("script.php", { action: "blah"},
   function(data) {
     alert("Data Loaded: " + data);
   });
}

function loadxml3()
{
	$.post("main.php", function(data) {
     alert("Status :" + data);
   });
}

</script>

<script src="jquery-mobile/jquery.mobile-1.0a3.min.js" type="text/javascript"></script>
<!-- This reference to phonegap.js will allow for code hints as long as the current site has been configured as a mobile application. 
	 To configure the site as a mobile application, go to Site -> Mobile Applications -> Configure Application Framework... -->
<script src="/phonegap.js" type="text/javascript"></script>
<link href="styles/custom.css" rel="stylesheet" type="text/css">
</head> 
<body> 

<div data-role="page" id="page">
	<div data-role="header">
    	<a href="main.php" rel="external" data-role="button" data-icon="arrow-l">Home</a>
		<h1>Settings</h1>
	</div>
    
	<div data-role="content">	
		<ul data-role="listview" data-inset="true">
			<li><a href="#page2" data-transition="slideup">Server Address</a></li>
            <li><a href="#page3" data-transition="slideup">Server Port</a></li>
			<li><a href="#page4" data-transition="slideup">Server Password</a></li>
		</ul>		
	</div>
      
    

<div data-role="footer" style="POSITION: absolute; BOTTOM: 0px; WIDTH: 100%;">
		<h4>BetaHomes &copy; 2012</h4>
	</div>
</div>

<div data-role="page" id="page2"> 
	<div data-role="header">
    	<a href="#page" rel="external" data-role="button" data-icon="arrow-l" data-transition="slidedown">Back</a>
		<h1>Server Address</h1>
	</div>
    
	<div data-role="content">	
		Content		
	</div>
    
	<div data-role="footer">
		<h4>BetaHomes &copy; 2012</h4>
	</div>
</div>



<div data-role="page" id="page3" align="center">
	<div data-role="header">
    	<a href="#page" rel="external" data-role="button" data-icon="arrow-l" data-transition="slidedown">Back</a>
		<h1>Server Port</h1>
	</div>

	
    <label for="settingsPort" style="width:150px; margin-top:30px;">Input Server Port</label>
    
	<center>
    <div data-role="fieldcontain" style="width:60%; padding-top:5px; margin:0px auto;">
      <input type="text" pattern="[0-9]*" name="settingsPort" id="settingsPort" value="" />
      <br>
      <input onClick="loadxml()" name="settingsSetPortBtn" type="submit" id="settingsSetPortBtn" value="Set" data-icon="gear" data-theme="c" />
    </div>
	</center>
    
    <input onClick="loadxml2()" name="settingsSetPortBtn2" type="submit" id="settingsSetPortBtn2" value="Set2" data-theme="c" />
    <br>
    <input onClick="loadxml3()" name="settingsSetPortBtn3" type="submit" id="settingsSetPortBtn3" value="Set3" data-theme="c" />

	<div data-role="footer" style="position:absolute;">
		<h4>BetaHomes &copy; 2012</h4>
  	</div>

    
    
</div>



<div data-role="page" id="page4">
	<div data-role="header">
    	<a href="#page" rel="external" data-role="button" data-icon="arrow-l" data-transition="slidedown">Back</a>
		<h1>Server Password</h1>
	</div>
    
	<div data-role="content">	
		Port Number is : <?php echo $_POST["settingsPort"]; ?><br />		
	</div>
    
	<div data-role="footer">
		<h4>BetaHomes &copy; 2012</h4>
	</div>
</div>

</body>
</html>