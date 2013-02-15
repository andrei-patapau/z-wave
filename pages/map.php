<?php
session_start();
if(!isset($_SESSION["myusername"])){
header("location:/Logout.php");//clean session and return to index.php
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../includes/imports.php"); ?>
<script>
$(function() { 
    $(".foo").sortable({ 
        stop: function(event, ui) { 
            var itemID = $(ui.item).attr("id"); 
            var items = $("li#" + itemID).parent(".foo").children(); 
			//alert(itemID + "-" + items);
			
            //var updateditems = new Array(); 
 
            for (var i = 0; i <= items.length - 1; i++) { 
                var singleitemID = $(items[i]).attr("id"); 
                var loc = i + 1; 
 
                $("#" + singleitemID).text(loc); 
 
 
                //updateditems.push([singleitemID, loc]); 
            } 
			
        } 
    }); 
}); 



</script>

<title>BetaHomes</title>
</head>
<body>
<div id="wrapper">
	<div class="container" style="border-style:groove;">

	<?php include("../includes/header.php"); ?>


	<center>
	<!-- START BODY OF CONTAINER WITHIN CENTER TAG -->
	<div id="divBorder">

	
	<h1 style="color: #439deb;">Map Page</h1>
	
	<ul class="foo"> 
	  <li id="asdf"> 
		<span class="indexnumber"></span> 
		<span class="description">whatever</span> 
	  </li> 
	 
	  <li id="asdfasdf"> 
		<span class="indexnumber"></span> 
		<span class="description">whatever</span> 
	  </li> 
	</ul> 


	</div>
	<!-- END BODY OF CONTAINER WITHIN CENTER TAG -->
	</center>
	</div><!-- end .container -->
	<div class="push"></div>
</div><!-- end #wrapper -->
<?php include("../includes/footer.php"); ?>

</body>
</html>
