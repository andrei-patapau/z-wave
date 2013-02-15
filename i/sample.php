
<?php 

$phpVar = 88;

function hello()
{
	global $phpVar;
	$new = $phpVar + 12;
	echo ($new);
}

function relocate()
{
	header( 'Location: http://www.google.com/' ); 
}

?>


