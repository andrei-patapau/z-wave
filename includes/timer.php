
<?php

$timer = new timer();

if(isset($_POST['action']) && !empty($_POST['action'])) {
    $action = $_POST['action'];
    switch($action) {
	
		case 'currTime' : $timer->currTime(); break;

    }
}
//startPHP
//////////////////
class timer
{	
	function currTime()
	{
		echo date("G : i : s");
	}
}

?>






















