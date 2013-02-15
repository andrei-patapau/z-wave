<?php

if(isset($_POST['action']) && !empty($_POST['action'])) {
    $action = $_POST['action'];
    switch($action) {
        case 'test' : test();break;
        case 'blah' : blah();break;
		case 'connect' : connect();break;
        // ...etc...
    }
}

function test()
{

	echo 'Test Passed!!!';
}

function blah()
{

	echo 'Blah Passed!!!';
}

function connect()
{
	echo 'Connecting';
}


    //if($_GET['event'] == 'button_clicked')
     //   echo "\"You clicked a button\"";
?>
