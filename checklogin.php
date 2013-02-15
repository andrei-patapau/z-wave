<?php

require_once 'connection.php';

$data = new dataClass();

if(isset($_POST['action']) && !empty($_POST['action'])) {
    $action = $_POST['action'];
    switch($action) {
		case 'checkLogin' : $data->checkLogin(); break;
    }
}
//startPHP
//////////////////
class dataClass
{	

	function checkLogin()
	{
		
		// username and password sent from form 
		$myusername=$_POST['myusername']; 
		$mypassword=$_POST['mypassword']; 

		// To protect MySQL injection (more detail about MySQL injection)
		$myusername = stripslashes($myusername);
		$myusername = mysql_real_escape_string($myusername);
		
		$sql="SELECT * FROM user WHERE username='$myusername'";
		$result=mysql_query($sql);
		
		$currentUser = mysql_fetch_array($result);
		$salt = $currentUser['salt'];
		
		$mypassword = stripslashes($mypassword);
		$mypassword = mysql_real_escape_string($mypassword);
		
		//$saltedMD5Pass = md5($mypassword . $salt);
		$saltedSHA256Pass = hash('sha256', $mypassword . $salt);
		
		
		$sql="SELECT * FROM user WHERE username='$myusername' and password='$saltedSHA256Pass'";
		$result=mysql_query($sql);

		// Mysql_num_row is counting table row
		$count=mysql_num_rows($result);

		// If result matched $myusername and $mypassword, table row must be 1 row
		if($count==1){
			// Register $myusername, $mypassword and redirect to file "main.php"
			//session_register("myusername");
			
			$admin = $currentUser['admin'];
			
			//change salt
			//update md5 password
			//****************************************
			$salt = $this->generateSalt();
			//$newSaltedMD5Pass = md5($mypassword . $salt);
			$newSaltedSHA256Pass = hash('sha256', $mypassword . $salt);
			$sql="UPDATE `user` SET `password`='$newSaltedSHA256Pass', `salt`='$salt' WHERE username='$myusername' and password='$saltedSHA256Pass'";
			$result=mysql_query($sql);
			//****************************************
			
			session_start();
			$_SESSION["myusername"] = $myusername;
			$_SESSION["mypassword"] = $newSaltedSHA256Pass;
			$_SESSION["admin"] = $admin;
			echo "Success";
		}
		else {
		//echo '<script language="javascript">alert("Wrong username or password!")</script>';
		//header("location:index.php?something='blablabla'");
		//send_to_host('','POST','/index.php','something=5');
		//header("location:index.php");
			echo "Login Failed<br>Wrong Username or Password";
		}
		
		
	}
	
	function generateSalt($max = 64) {
	
		$characterList = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*?";
		$i = 0;
		$salt = "";
		while ($i < $max) {
			$salt .= $characterList{mt_rand(0, (strlen($characterList) - 1))};
			$i++;
		}
		return $salt;
	}
	
	
}



?>