<?php
/*
session_start();

if(!isset($_SESSION["myusername"])){
header("location:/Logout.php");//clean session and return to index.php
}
*/

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php require_once 'connection.php';  //style="font-size:62.5%;" ?>

<?php include("includes/imports.php"); ?>
<script type="text/javascript" src="/includes/zwave_scripts.js"></script>





<title>BetaHomes</title>
</head>
<body>
<div id="wrapper">
	<div class="container" style="border-style:groove;">
		<?php include("includes/header.php"); ?>
		<!-- START BODY OF CONTAINER WITHIN CENTER TAG -->
			<div id="divBorder">
<!----------------------------------------------------------------------------------------------------------------->

<h1 style="color: #439deb;">iPhone Page</h1>

<!----------------------------------------------------------------------------------------------------------------->
			</div>
		<!-- END BODY OF CONTAINER WITHIN CENTER TAG -->
	</div><!-- end .container -->
	<div class="push"></div>
</div><!-- end #wrapper -->
<?php include("includes/footer.php"); ?>

</body>
</html>
