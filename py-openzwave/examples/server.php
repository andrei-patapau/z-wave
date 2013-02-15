<?php
//$DelFilePath = "../zwcfg_0x014d0d27.xml";
//if (file_exists($DelFilePath)) { unlink ($DelFilePath); }

$execute_every_n_seconds = 10800; // 10800sec = 3 hours
//----------------------------------------------------------------------------------------

require_once '/var/www/connection.php';

//mysql_query("UPDATE `home` SET `server_2` = 1 WHERE `id` = 1");
//$something = $_SERVER['PATH_TRANSLATED'];//$_SERVER['REQUEST_URI'];//$_SERVER['PHP_SELF'];//getcwd();
//echo $something;



$functionsClass = new FUNC_CLASS();
//----------------------------------------------------------------------------------------
$port = $functionsClass->getPortNumber("fcall");
//return $port;
//if port found we pass /dev/ttyUSB<port_number> => strpos should find our string at 
//location 0(beginning). Otherwise, string doesn't exist.
$usb_found = strpos($port,'USB');
//========================================================================================
//return $port . "---" . $usb_found . "\n"; -PASS
$i = 0; // Represent number of attempts server_2 ran


while(true){

	//----------------------------------------------------------------------------------------
	$result = mysql_query("SELECT server_2 FROM home WHERE id = 1");
	$server_2 = mysql_fetch_array($result); // 0 => stop execution; 1 => continue
	$server_2 = $server_2['server_2']; //Just reusing the same variable(why not)
	//----------------------------------------------------------------------------------------
	if($server_2 && $usb_found){
		
		// 1) Disable server_1
		mysql_query("UPDATE `home` SET `server_1` = 0 WHERE `id` = 1");
		
		// 2) Wait a few seconds to make sure it shut down
		sleep(3);
		
		// 3) Get current location (must be "/var/www/py-openzwave/examples")
		$currentLocation = getcwd();
		
		//echo "Executing";
		
		// 4) Safe remove of XML file if exist
		if ($handle = opendir("$currentLocation")) {
			while (false !== ($entry = readdir($handle))) {
				if ((strpos($entry, 'zwcfg_') !== false) && (strpos($entry, '.xml') !== false)){
					//Before removing file need to get it's data
					unlink ("$currentLocation/$entry");
					echo "\n$entry has been Removed\n"; 
				}
			}
			closedir($handle);
		}
		
		

		// 5) execute python script which will generate in current location zwcfg_*.xml file
		$str = shell_exec("python " . "$currentLocation/myFile.py " . $port);
		
		$success_found = strpos($str,'SUCCESS');
		//echo $success_found;
		if($success_found){

			$identical = $functionsClass->comparator();
			//echo $identical;
			
			if($identical == "false"){
				// remove main xml 
				if ($handle = opendir("/var/www/")) {
					while (false !== ($entry = readdir($handle))) {
						if ((strpos($entry, 'zwcfg_') !== false) && (strpos($entry, '.xml') !== false)){
							//Before removing file need to get it's data
							unlink ("/var/www/$entry");
							echo "\n$entry has been Removed\n"; 
						}
					}
					closedir($handle);
				}
			}
			
			// 1) Enable server_1
			mysql_query("UPDATE `home` SET `server_1` = 1 WHERE `id` = 1");
			//----------------------------------------------------------------------------------------
			//**************
			//**************
			//remove this block after done
			echo "BREAK";
			break;
			//**************
			//**************
			// END: Sleep for 3 hours and repeat this process again
			sleep($execute_every_n_seconds);
			// Reset number of attempts to 0
			$i = 0;
		}
		else{
			echo "$i - FAIL";
			$i = $i + 1;//attempt fail, increment 'i'
			// After 3 attempts resume server_1. Report Error. Try again in 3 hours.
			if($i % 3 == 0){
				mysql_query("UPDATE `home` SET `server_1` = 1 WHERE `id` = 1");
				//report an error//
				echo "Sleeping for $execute_every_n_seconds seconds";
				sleep($execute_every_n_seconds);
				// Reset number of attempts to 0
				$i = 0;
			}
		}
	}
	else{
		echo "Execution of Network Node Status is halted.";
		sleep(5);
	}
}

echo "Server Execution been stopped";

//========================================================================================
class FUNC_CLASS
{
	public function comparator(){

		$feedback = "true";
		//get XML file name
		$file_name_2 = "file is missing";
		if ($handle = opendir(getcwd())) {
			while (false !== ($entry = readdir($handle))) {
				if ((strpos($entry, 'zwcfg_') !== false) && (strpos($entry, '.xml') !== false)){
					$file_name_2 = $entry;
				}
			}
			closedir($handle);
		}
		
		$something = getcwd();
		
		//get XML file name
		$file_name_1 = "file is missing";
		if ($handle = opendir("/var/www/")) {
			while (false !== ($entry = readdir($handle))) {
				if ((strpos($entry, 'zwcfg_') !== false) && (strpos($entry, '.xml') !== false)){
					$file_name_1 = $entry;
				}
			}
			closedir($handle);
		}
		

		
		
		if(($file_name_1 != "file is missing") && ($file_name_2 != "file is missing")){
			//load XML 1
			$nodes_1 = simplexml_load_file("/var/www/" . $file_name_1);
			//load XML 2
			$nodes_2 = simplexml_load_file(getcwd(). "/" . $file_name_2);
			
			//-----------------------------------------------------------------
			//check nodes count
			$nodes_1_count = 0; $home_id_1 = "none";
			$nodes_2_count = 0; $home_id_2 = "none";
			foreach ($nodes_1 as $node){
				$nodes_1_count++;
				$home_id_1 = (string)$nodes_1['home_id'];
			}
			foreach ($nodes_2 as $node){
				$nodes_2_count++;
				$home_id_2 = (string)$nodes_2['home_id'];
			}
			if($nodes_1_count != $nodes_2_count){
				$feedback = "false";// . $nodes_1_count . "-" . $nodes_2_count;
				return $feedback;
			}
			if($home_id_2 != $home_id_1){
				$feedback = "false";// . $home_id_2 . "-" . $home_id_1;
				return $feedback;
			}
			
			//-----------------------------------------------------------------
			foreach ($nodes_1 as $node){
				$manufacturerID = $node->Manufacturer['id'];
				
				if($manufacturerID){
					$id = $node['id'];
					$productType = $node->Manufacturer->Product['type'];
					$productID = $node->Manufacturer->Product['id'];
					
					$result = mysql_query("SELECT * FROM nodes WHERE node_id = '$id'");
					$db_node = mysql_fetch_array($result);
					
					$manufacturer_id = $db_node['manufacturer_id']; 
					$product_type_id = $db_node['product_type_id']; 
					$product_id = $db_node['product_id'];
					
					if(($manufacturer_id != $manufacturerID) || ($productType != $product_type_id) || ($productID != $product_id)){
						$feedback = "false";
						return $feedback;
					}
				}
			}
			
			//-----------------------------------------------------------------
			
			return $feedback;
			
		
		}

		
		/*
		//output home id
		echo "Home ID: " . $nodes['home_id'] . "<br><br>";
		
		foreach ($nodes as $node){
			$id=$node['id'];
			$manufacturerID=$node->Manufacturer['id'];
			$productType=$node->Manufacturer->Product['type'];
			$productID=$node->Manufacturer->Product['id'];
			echo "Node ID: " . $id . " | " . "Manufacturer ID: " . $manufacturerID . " | " . "Product Type: " . $productType . " | " . "Product ID: " . $productID . "<br>";
			
			$commandClasses = $node->CommandClasses->CommandClass;
			echo "<UL>";
			foreach ($commandClasses as $commandClass){
				
				$cmID = $commandClass['id'];
				$cmName = $commandClass['name'];
				echo "CommandClass ID: " . $cmID . " |  Name: " . $cmName . "<br>";
			}
			echo "</UL>";
		}
		*/
	}
	
	public function getPortNumber($call_type){
		
		// ' 2>&1' => even when a result is an error, it returns an error string which would contain original request ('/dev/ttyUSB*')
		// Therefore, if '/dev/ttyUSB*' found => USB port was not found => return 'Adapter Not Found'
		$output = shell_exec('ls -l /dev/ttyUSB*'.' 2>&1'); 
		$usb_not_found = strpos($output,'USB*');
		//return $usb_not_found;
		
		if($usb_not_found === true){
			if ($call_type == "fcall")
				return "Adapter Not Found";
			else
				echo "Adapter Not Found";
		}
		else{
			//reverse string
			$reverse = strrev($output);

			//get number of USB port
			$usb_port = $reverse{1};

			if(is_numeric($usb_port))
				if ($call_type == "fcall")
					return "/dev/ttyUSB$usb_port";
				else
					echo "/dev/ttyUSB$usb_port";
			else 
			{
				//Sometimes for unknown reason EOL character is missing, 
				//therefore check if USB port is a first character
				$usb_port = $reverse{0};
				
				if(is_numeric($usb_port))
					if ($call_type == "fcall")
						return "/dev/ttyUSB$usb_port";
					else
						echo "/dev/ttyUSB$usb_port";
				else 
					if ($call_type == "fcall")
						return "Device is missing.";
					else
						echo "Device is missing.";
			}
		}

	}
}

?>