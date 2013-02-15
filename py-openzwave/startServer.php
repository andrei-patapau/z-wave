<?php require_once '/var/www/connection.php'; ?>
<?php require_once '/var/www/execute.php'; ?>

<?php

//chdir(dirname($_SERVER['SCRIPT_FILENAME']));
$serverpy = new serserPY();
/*
echo getcwd();
sleep(3);
echo "  second";
*/

mysql_select_db('currentState');
//----------------------------------------------------------------------------------------
$dim_level = 0;


$message = "";
$run_counter = 0;
$log_file = '/var/www/log_file.log';
$message =  "\n====================================================================================\n";
$timestamp = date('d/m/Y H:i:s');
$message = $message . "Log Start: [" . $timestamp . "]";
$message = $message . "\n====================================================================================";
//------------------------------------------------------------
//Log File
error_log($message.PHP_EOL, 3, $log_file);
//------------------------------------------------------------

while(true){

	$run_counter++;
	$message = "---$run_counter\n";
	
	//ob_clean();//Clean (erase) the output buffer (does not destroy the output buffer)
	ob_start();//Turn on output buffering
	$serverpy->testServer();
	$value = ob_get_contents();//Return the contents of the output buffer
	ob_end_clean();//Clean (erase) the output buffer and turn off output buffering
	
	//$message = $message .  $serverpy->testServer();
	$message = $message .  $value;
	
	//------------------------------------------------------------
	//Log File
	error_log($message.PHP_EOL, 3, $log_file);
	//------------------------------------------------------------
 
	sleep(1);
	
	//------------------------------------------------------------
	//check if shutdown activated
	$result = mysql_query("SELECT * FROM home WHERE id = 1");
	$server_1_db = mysql_fetch_array($result); // 1 => stop execution; 0 => continue
	$server_1_exit = $server_1_db['server_1_exit']; //Just reusing the same variable(why not)
	if($server_1_exit){//emergency shut down
		//mysql_query("UPDATE `home` SET `server_1_exit`= '0' WHERE `id` = '1'");
		break;
	}
	//------------------------------------------------------------
}
$timestamp = date('d/m/Y H:i:s');
$message = "\n====================================================================================\n" . "Log End: [" . $timestamp . "]" . "\n====================================================================================\n";
//------------------------------------------------------------
//Log File
error_log($message.PHP_EOL, 3, $log_file);
//------------------------------------------------------------


class serserPY
{
	public function testServer(){
		//----------------------------------------------------------------------------------------
		///select database
		mysql_select_db('currentState');
		//----------------------------------------------------------------------------------------
		$result = mysql_query("SELECT server_1 FROM home WHERE id = 1");
		$server_1 = mysql_fetch_array($result); // 0 => stop execution; 1 => continue
		$server_1 = $server_1['server_1']; //Just reusing the same variable(why not)
		//----------------------------------------------------------------------------------------
		$port = $this->getPortNumber("fcall");
		//echo $port;
		//if port found we pass /dev/ttyUSB<port_number> => strpos should find our string at 
		//location 0(beginning). Otherwise, string doesn't exist.
		$usb_found = strpos($port,'USB');
	
		//need to stop execution of all service applications in case XML file needs to be updated
		if($usb_found){
			//----------------------------------------------------------------------------------------
			$result = mysql_query("SELECT xml_remove FROM home WHERE id = 1");
			$xml_remove = mysql_fetch_array($result); // 1 => remove data; 0 => do nothing
			$xml_remove = $xml_remove['xml_remove']; //Just reusing the same variable(why not)
			// Do if xml_remove flag been set
			if($xml_remove){
				mysql_query("UPDATE `home` SET `xml_remove`= '0' WHERE `id` = '1'"); //unset xml_remove flag
				mysql_query("UPDATE `home` SET `reload_nodes`= '1' WHERE `id` = '1'"); //set flag to reset database
				$xml_remove_feedback = $this->removeXML();
				echo $xml_remove_feedback;
			}
			//----------------------------------------------------------------------------------------
			//check if it's a first run. If yes => clean database/reload/remove all nodes
			$result = mysql_query("SELECT reload_nodes FROM home WHERE id = 1");
			$reload_nodes = mysql_fetch_array($result); // 1 => remove data; 0 => do nothing
			$reload_nodes = $reload_nodes['reload_nodes']; //Just reusing the same variable(why not)
			if($reload_nodes != 0){
				//remove
				//set to 0
				$result = mysql_query("DELETE FROM `nodes` WHERE 1"); //remove all data from table 'nodes'
				$result = mysql_query("DELETE FROM `_nodes_` WHERE 1"); //remove all data from table '_nodes_'
				$updateQuery = "UPDATE `home` SET `reload_nodes`= '0' WHERE `id` = '1'"; //unset reload nodes
				echo mysql_query($updateQuery); //set reload_nodes to 0 (don't reload anymore)
			}
			//=============================================================================================================================
			//=============================================================================================================================
			// MUST BE START OF SERVER_1 check
			if($server_1){
				//----------------------------------------------------------------------------------------
				//$currentLocation = getcwd();
				//currentLocation = /var/www/py-openzwave
				$str = shell_exec("python " . "/var/www/py-openzwave/examples/testServerFile.py " . $port);
				$order   = array("[", "'", "]", ",");
				$replace = '';
				$str = str_replace($order, $replace, $str);
				$nodeArray = explode(" ", $str); //[node_id, isOn, level]...
				// nodeArray contains node_id, isOn and level sequences for all nodes
				$nodeArrayLength = count($nodeArray); //returns number of elements in nodeArray
				$found = false;
				//----------------------------------------------------------------------------------------
				//update home_id and hash of xml file
				$getHomeId_hash_array = $this->getXML_Home_id();
				$getHomeId_length = count($getHomeId_hash_array);
				$getHomeId = (string)$getHomeId_hash_array[$getHomeId_length - 2];
				$getHash = (string)$getHomeId_hash_array[$getHomeId_length - 1];
				mysql_query("UPDATE `home` SET `home_id`='$getHomeId',`md5_hash_of_xml`='$getHash' WHERE id = 1");
				//----------------------------------------------------------------------------------------
				// runs for each node that has been found
				//loads data and keeps track of isON and level
				//TODO: add manifacturer_name and product_name
				echo "id --- isON --- level<br>";
				for($i = 0; $i < $nodeArrayLength; $i = $i + 3){
					$i1 = $i; //node id index
					$i2 = $i + 1; //node isON index
					$i3 = $i + 2; //node level index
					
					//a bit inefficient approach, but it works!
					$result = mysql_query("SELECT * FROM nodes");
					while($row = mysql_fetch_array($result)){
						if($row['node_id'] == $nodeArray[$i]){
							$found = true; //node exist
							$tempNodeID = $row['node_id'];
							$tempNodeIsON = $row['isON'];
							$tempNodeLevel = $row['level'];
						}
					}
					 
					//check first if node Manufacturer ID exist. If yes => do everything bellow, if no => remove node from database
					//=============================================================================================================================
					$homeid_manifactureid_string = $this->getXMLData($nodeArray[$i]);
					$homeid_manifactureid_array = explode(" ", $homeid_manifactureid_string); //[ home_id, manufacturerID ] ... productType, productID
					
					//echo $homeid_manifactureid_array[0] . "-" . $homeid_manifactureid_array[1] . "-" . $homeid_manifactureid_array[2] . "-" . $homeid_manifactureid_array[3] . "\n";
					$manufacturer_id = (string)$homeid_manifactureid_array[1];
					$product_type_id = (string)$homeid_manifactureid_array[2];
					$product_id = (string)$homeid_manifactureid_array[3];
					
					// if manifacture id exist => productType, productID exist too => 
					if($homeid_manifactureid_array[1] != "-"){
					
						//echo $homeid_manifactureid_array[0] . "-" . $homeid_manifactureid_array[1] . "-" . $homeid_manifactureid_array[2] . "-" . $homeid_manifactureid_array[3] . "\n";
						//At this point we know that manufacturer_id, product_type_id and product_id exist. Now let's get manifacturer_name and product_name from manufacturer_specific.xml
						//=============================================================================================================================
						$manufacturer_specifics_array = $this->getManufacturerSpecificXML($manufacturer_id, $product_type_id, $product_id);
						$manufacturer_specifics_array_length = count($manufacturer_specifics_array);
						
						$manifacturer_name = (string)$manufacturer_specifics_array[$manufacturer_specifics_array_length - 2];
						$product_name = (string)$manufacturer_specifics_array[$manufacturer_specifics_array_length - 1];
						
						//=============================================================================================================================

						//=============================================================================================================================
						// node id exist in database
						if($found){
							
							//%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=
							//check here if isON been changed					
							//if so => update isON status
							
							$isON_alpha = (string)$nodeArray[$i2];
							$isON_beta = (string)$tempNodeIsON;
							if($isON_alpha != $isON_beta){
								mysql_query("UPDATE `nodes` SET `isON`= '$nodeArray[$i2]' WHERE `node_id` = '$tempNodeID'");
								mysql_query("UPDATE `_nodes_` SET `isON`= '$nodeArray[$i2]' WHERE `node_id` = '$tempNodeID'");
								//echo "update warning: node_id - $tempNodeID; isON - $nodeArray[$i2]\n";
							}
							//%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=
							//check here if level been changed
							
							$level_alpha = (int)$nodeArray[$i3];
							$level_beta = (int)$tempNodeLevel;
							if($level_alpha != $level_beta){
								// if so => update level
								mysql_query("UPDATE `nodes` SET `level`= '$nodeArray[$i3]' WHERE `node_id` = '$tempNodeID'");
								mysql_query("UPDATE `_nodes_` SET `level`= '$nodeArray[$i3]' WHERE `node_id` = '$tempNodeID'");
								//echo "update warning: node_id - $tempNodeID; level - '$level_alpha' - temp level - '$level_beta'\n";
							}
							//%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=
							
							echo $tempNodeID . " ---- " . $tempNodeIsON . " --- " . $tempNodeLevel . "<br>";
							
						}
						else{
							echo "\n" . $manufacturer_id . "-" . $product_type_id . "-" . $product_id . "  -  added to mysql\n";
							//add a new node to DB if it doesn't exist yet
							mysql_query("INSERT INTO `nodes`(`node_id`, `isON`, `level`) VALUES ('$nodeArray[$i1]', '$nodeArray[$i2]', '$nodeArray[$i3]')");
							mysql_query("INSERT INTO `_nodes_`(`node_id`, `isON`, `level`) VALUES ('$nodeArray[$i1]', '$nodeArray[$i2]', '$nodeArray[$i3]')");
							//update node by setting manufacturerID, productType, productID
							mysql_query("UPDATE `nodes` SET `manufacturer_id`='$manufacturer_id',`product_type_id`='$product_type_id',`product_id`='$product_id' WHERE `node_id` = '$nodeArray[$i]'");
							mysql_query("UPDATE `_nodes_` SET `manufacturer_id`='$manufacturer_id',`product_type_id`='$product_type_id',`product_id`='$product_id' WHERE `node_id` = '$nodeArray[$i]'");
							//update node by setting manifacturer_name, product_name
							mysql_query("UPDATE `nodes` SET `manifacturer_name`='$manifacturer_name',`product_name`='$product_name' WHERE `node_id` = '$nodeArray[$i]'");
							mysql_query("UPDATE `_nodes_` SET `manifacturer_name`='$manifacturer_name',`product_name`='$product_name' WHERE `node_id` = '$nodeArray[$i]'");
							//%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=
							//Load Command Classes
							$command_classes_array = $this->getCommandClassesXML($nodeArray[$i1]);
							$command_classes_array_length = count($command_classes_array);
							//echo "$command_classes_array_length\n";
							for($j = 0; $j < $command_classes_array_length; $j = $j + 3){
								$j1 = $j;
								$j2 = $j + 1;
								$j3 = $j + 2;
								mysql_query("INSERT INTO `command_classes`(`node_id`, `id`, `name`) VALUES ('$command_classes_array[$j1]', '$command_classes_array[$j2]', '$command_classes_array[$j3]')");
								
							}
							//%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=%=
						}
						
						$found = false;
						//echo $nodeArray[$i] . $nodeArray[$i+1] . $nodeArray[$i+2] . " - ";
						//=============================================================================================================================
						
					}
					else{
						//currently do nothing. Don't add, don't update, node doesn't exist.
					}
				}
			}
			else{
				echo "Execution is halted.";
				sleep(1);
			}
			// MUST BE END OF SERVER_1 check
			//=============================================================================================================================
			//=============================================================================================================================
			//=============================================================================================================================
			//=============================================================================================================================
			//Check Rules
			
			
			$this->checkRules();
			
			
			//=============================================================================================================================
			//=============================================================================================================================
			//=============================================================================================================================
			//Check Schedules
			
			
			$this->schedulesOnce();
			$this->schedulesEveryDay();
			$this->schedulesWeekly();
			$this->schedulesInterval();
			
			
			//=============================================================================================================================
			//=============================================================================================================================

			//compare DB _nodes_ vs nodes
			//if different => execute pl script
			//$port is given
			$nodes = mysql_query("SELECT * FROM nodes");
			
			/*
			//random light intensity generator
			if(($row != null) && ($_row_ != null)){
				$dim_level = rand(0, 99);
				shell_exec("perl z-waver.pl '$port' dim 9 '$dim_level'");
			}
			*/
			
			while($row = mysql_fetch_array($nodes)){
				$curr_db_id = $row['node_id'];
				$curr_db_isON = $row['isON'];
				$curr_db_level = $row['level'];
				
				$_nodes_ = mysql_query("SELECT * FROM _nodes_ WHERE node_id = '$curr_db_id'");
				$_row_= mysql_fetch_array($_nodes_);
				
				$temp_db_isON = $_row_['isON'];
				if($curr_db_isON != $temp_db_isON){
					if($temp_db_isON == "False"){
						$temp_db_isON = "off";
					}
					else{
						$temp_db_isON = "on";
					}
					
					shell_exec("perl z-waver.pl '$port' switch '$curr_db_id' '$temp_db_isON'");
				}
				
				$temp_db_level = $_row_['level'];
				
				echo $curr_db_id . "--" . $curr_db_level . "---" . $temp_db_level . "\n";
				
				if($curr_db_level != $temp_db_level){
					shell_exec("perl z-waver.pl '$port' dim '$curr_db_id' '$temp_db_level'");
					echo "\nScript Executed for temp id = $curr_db_id -- level = $temp_db_level\n";
				}
				
			}

		//=============================================================================================================================
		//=============================================================================================================================
		}
		else{
			echo "USB adapter not found.";

		}
		

	}
	
	//schedulesInterval
	public function schedulesInterval(){

		$schedulesInterval = mysql_query("SELECT * FROM `schedulesInterval`");
		//`id`, `title`, `timeHH`, `timeMM`, `timeAMPM`, `date`, `timeHzDD`, `timeHzHH`, `timeHzMM`, `active`, `nid`, `level`, `isON`, `dateTime`
		
		while($schedule = mysql_fetch_array($schedulesInterval)){
			
			//do something only if schedule is active
			if($schedule['active'] == "1"){
				//check date
				$date = $schedule['date'];
				//get todays date
				$today = date("m/d/Y");
				
				//Break Date into dd/mm/yyyy
				$dateArray = explode("/", $today);
				$todayMonth = intval($dateArray[0]);
				$todayDay = intval($dateArray[1]);
				$todayYear = intval($dateArray[2]);
				
				$today = $todayMonth . "/" . $todayDay . "/" . $todayYear;
				
				//echo "$date - $today -------------------------------------------\n";
				//do something only if date matches
				if($today == $date){
					//echo "Step 0";
					//do something only if hour matches
					$timeHH = $schedule['timeHH'];
					if($timeHH == date("G")){
						//echo "Step 1";
						//do something only if Minute matches
						$timeMM = $schedule['timeMM'];
						if($timeMM == date('i')){
							//////////////////////////////////////////////////////////////////////////////
							//echo "Step 2";
							if($schedule['dateTime'] != date("i/G/m/d/Y")){
							
								$nid = $schedule['nid'];//get node ID
								$scheduleId = $schedule['id'];//get schedule id
							
								//set 'dateTime' to current time
								$currDateTime = date("i/G/m/d/Y");
								mysql_query("UPDATE `schedulesInterval` SET `dateTime`= '$currDateTime' WHERE `id` = '$scheduleId'");
								echo "$currDateTime -------------------------------------------\n";
								//Determine the type of execution rule
								if(($schedule['isON'] != "-") && ($schedule['level'] == "-")){
									$isOn = "False";
									//Socket
									if($schedule['isON'] == "On")
										$isOn = "True";
									
									mysql_query("UPDATE `_nodes_` SET `isON`= '$isOn' WHERE `node_id` = '$nid'");
									
								}else if(($schedule['isON'] == "-") && ($schedule['level'] != "-")){
									//Dimmer
									$level = $schedule['level'];
									mysql_query("UPDATE `_nodes_` SET `level`= '$level' WHERE `node_id` = '$nid'");
								}
								else{
									//Controller
									//Do nothing
								}
								
								//update next execution date and time
								$this->updateSchedulesIntervalDateTime($scheduleId);
								
							}
							//////////////////////////////////////////////////////////////////////////////
						}

					}

				}

			}
		}
	}
	
	public function updateSchedulesIntervalDateTime($scheduleId){
		//`id`, `title`, `timeHH`, `timeMM`, `timeAMPM`, `date`, `timeHzDD`, `timeHzHH`, `timeHzMM`, `active`, `nid`, `level`, `isON`, `dateTime`
		$interval = mysql_query("SELECT * FROM `schedulesInterval` WHERE id = '$scheduleId'");
		$interval = mysql_fetch_array($interval);
		
		
		$timeHH = $interval['timeHH'];
		$timeMM = $interval['timeMM'];
		$date = $interval['date'];
		
		//Break Date into dd/mm/yyyy
		$dateArray = explode("/", $date);
		$month = intval($dateArray[0]);
		$day = intval($dateArray[1]);
		$year = intval($dateArray[2]);
		//
		
		
		$timeHzDD = $interval['timeHzDD'];
		$timeHzHH = $interval['timeHzHH'];
		$timeHzMM = $interval['timeHzMM'];
		
		
		//$stringToEcho = $scheduleId . "--" . $timeMM . "|" . $timeHH . "|" . $timeHHmil . "|" . $month . "|" . $day . "|" . $year . "|" . $date . "|" . $timeHzMM;
		//echo "{$timeMM} : ({$timeHH} | {$timeHHmil}) | {$month}/{$day}/{$year} - {$date}| $timeHzDD-$timeHzHH-{$timeHzMM} --------------------\n";
		
		
		
		//update minutes
		$newTimeMM = $timeMM + $timeHzMM;
		if($newTimeMM >= 60){
			$newTimeMM = $newTimeMM - 60;
			$timeHH = $timeHH + 1;
		}
		
		
		//update hours
		$newTimeHH = $timeHH + $timeHzHH;
		if($newTimeHH >= 24){
			$newTimeHH = $newTimeHH - 24;
			$day = $day + 1;
		}
		
		$day = $day + $timeHzDD;
		
		//
		//Need to update month and year
		//
		//**************************************************
		
		if(date("F") == "January"){
		//31
			if($day > 31){
				$day = $day - 31;
				$month = $month + 1;
			}
		}
		else if(date("F") == "February"){
		//28 or 29
			if(date("L")){
			//leap year => 29
				if($day > 29){
					$day = $day - 29;
					$month = $month + 1;
				}
			}
			else{
			//regular year => 28
				if($day > 28){
					$day = $day - 28;
					$month = $month + 1;
				}
			}
		
		}
		else if(date("F") == "March"){
		//31
			if($day > 31){
				$day = $day - 31;
				$month = $month + 1;
			}
		}
		else if(date("F") == "April"){
		//30
			if($day > 30){
				$day = $day - 30;
				$month = $month + 1;
			}
		}
		else if(date("F") == "May"){
		//31
			if($day > 31){
				$day = $day - 31;
				$month = $month + 1;
			}
		}
		else if(date("F") == "June"){
		//30
			if($day > 30){
				$day = $day - 30;
				$month = $month + 1;
			}
		}
		else if(date("F") == "July"){
		//31
			if($day > 31){
				$day = $day - 31;
				$month = $month + 1;
			}
		}
		else if(date("F") == "August"){
		//31
			if($day > 31){
				$day = $day - 31;
				$month = $month + 1;
			}
		}
		else if(date("F") == "September"){
		//30
			if($day > 30){
				$day = $day - 30;
				$month = $month + 1;
			}
		}
		else if(date("F") == "October"){
		//31
			if($day > 31){
				$day = $day - 31;
				$month = $month + 1;
			}
		}
		else if(date("F") == "November"){
		//30
			if($day > 30){
				$day = $day - 30;
				$month = $month + 1;
			}
		}
		else if(date("F") == "December"){
		//31
			if($day > 31){
				$day = $day - 31;
				$month = $month + 1;
			}
		}
		
		//update year
		if($month > 12){
			$month = $month - 12;
			$year = $year + 1;
		}
		

		//**************************************************
		$date = $month . "/" . $day . "/" . $year;

		
		mysql_query("UPDATE `schedulesInterval` SET `date`= '$date', `timeMM` = '$newTimeMM', `timeHH` = '$newTimeHH' WHERE `id` = '$scheduleId'");
		
		//$todayDateTime = date("i/G/m/d/Y");//min/hour/month/day/year
		//$minutes = intval(date('i'));
		
		
	}
	
	
	//schedulesWeekly
	public function schedulesWeekly(){

		$schedulesWeekly = mysql_query("SELECT * FROM `schedulesWeekly`");
		//`id`, `title`, `timeHH`, `timeMM`, `timeAMPM`, `mon`, `tue`, `wed`, `thu`, `fri`, `sat`, `sun`, `active`, `nid`, `level`, `isON`, `dateExecLast`
		
		while($schedule = mysql_fetch_array($schedulesWeekly)){
			
			//do something only if schedule is active
			if($schedule['active'] == "1"){

				//do something only if hour matches
				$timeHH = $schedule['timeHH'];
				if($timeHH == date("G")){
				
					//do something only if Minute matches
					$timeMM = $schedule['timeMM'];
					if($timeMM == intval(date('i'))){
						
						//check date
						$date = $schedule['dateExecLast'];
						//get todays date
						$today = date("m/d/Y");
						
						//do something only if date DOES NOT match
						//if date does match => schedule already been executed
						if($today != $date){
							
							//todays day of week
							$todayWeekDay = date("D");
							
							//if day of the week match
							$continue = false;//flag
							
							//*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!
							if(($schedule['mon']) && ($todayWeekDay == "Mon"))
								$continue = true;
							else if(($schedule['tue']) && ($todayWeekDay == "Tue"))
								$continue = true;	
							else if(($schedule['wed']) && ($todayWeekDay == "Wed"))
								$continue = true;	
							else if(($schedule['thu']) && ($todayWeekDay == "Thu"))
								$continue = true;	
							else if(($schedule['fri']) && ($todayWeekDay == "Fri"))
								$continue = true;	
							else if(($schedule['sat']) && ($todayWeekDay == "Sat"))
								$continue = true;
							else if(($schedule['sun']) && ($todayWeekDay == "Sun"))
								$continue = true;
							//*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!	
							
							if($continue){
								$scheduleId = $schedule['id'];//get schedule id
								mysql_query("UPDATE `schedulesWeekly` SET `dateExecLast`= '$today' WHERE `id` = '$scheduleId'");
							
								$nid = $schedule['nid'];//get node ID
								
								//echo "$nid - $scheduleId\n";
								//Determine the type of execution rule
								if(($schedule['isON'] != "-") && ($schedule['level'] == "-")){
									$isOn = "False";
									//Socket
									if($schedule['isON'] == "On")
										$isOn = "True";
									
									mysql_query("UPDATE `_nodes_` SET `isON`= '$isOn' WHERE `node_id` = '$nid'");
									
								}else if(($schedule['isON'] == "-") && ($schedule['level'] != "-")){
									//Dimmer
									$level = $schedule['level'];
									mysql_query("UPDATE `_nodes_` SET `level`= '$level' WHERE `node_id` = '$nid'");
								}
								else{
									//Controller
									//Do nothing
								}
							}
						}
					}
				}
			}
		}
	}
	
	//schedulesEveryDay
	public function schedulesEveryDay(){

		$schedulesEveryDay = mysql_query("SELECT * FROM `schedulesEveryDay`");
		//`id`, `title`, `timeHH`, `timeMM`, `timeAMPM`, `active`, `nid`, `level`, `isON`, `dateExecLast`
		
		while($schedule = mysql_fetch_array($schedulesEveryDay)){
			
			//do something only if schedule is active
			if($schedule['active'] == "1"){

				//do something only if hour matches
				$timeHH = $schedule['timeHH'];
				if($timeHH == date("G")){
				
					//do something only if Minute matches
					$timeMM = $schedule['timeMM'];
					if($timeMM == intval(date('i'))){
						
						//check date
						$date = $schedule['dateExecLast'];
						//get todays date
						$today = date("m/d/Y");
						
						//do something only if date DOES NOT match
						//if date does match => schedule already been executed
						if($today != $date){
					
							$scheduleId = $schedule['id'];//get schedule id
							mysql_query("UPDATE `schedulesEveryDay` SET `dateExecLast`= '$today' WHERE `id` = '$scheduleId'");
						
							$nid = $schedule['nid'];//get node ID
							
							//echo "$nid - $scheduleId\n";
							//Determine the type of execution rule
							if(($schedule['isON'] != "-") && ($schedule['level'] == "-")){
								$isOn = "False";
								//Socket
								if($schedule['isON'] == "On")
									$isOn = "True";
								
								mysql_query("UPDATE `_nodes_` SET `isON`= '$isOn' WHERE `node_id` = '$nid'");
								
							}else if(($schedule['isON'] == "-") && ($schedule['level'] != "-")){
								//Dimmer
								$level = $schedule['level'];
								mysql_query("UPDATE `_nodes_` SET `level`= '$level' WHERE `node_id` = '$nid'");
							}
							else{
								//Controller
								//Do nothing
							}
						}
					}
				}
			}
		}
	}
	
	//schedulesOnce
	public function schedulesOnce(){

		$schedulesOnce = mysql_query("SELECT * FROM `schedulesOnce`");
		//`id`, `title`, `timeHH`, `timeMM`, `timeAMPM`, `date`, `remove`, `active`, `nid`, `level`, `isON`
		
		while($schedule = mysql_fetch_array($schedulesOnce)){
			//do something only if schedule is active
			if($schedule['active'] == "1"){
				//check date
				$date = $schedule['date'];
				//get todays date
				$today = date("m/d/Y");
				
				//Break Date into dd/mm/yyyy
				$dateArray = explode("/", $today);
				$todayMonth = intval($dateArray[0]);
				$todayDay = intval($dateArray[1]);
				$todayYear = intval($dateArray[2]);
				
				$today = $todayMonth . "/" . $todayDay . "/" . $todayYear;
				
				//do something only if date matches
				if($today == $date){
					//do something only if hour matches
					$timeHH = $schedule['timeHH'];
					if($timeHH == date("G")){
						//do something only if Minute matches
						$timeMM = $schedule['timeMM'];
						if($timeMM == intval(date('i'))){
							$nid = $schedule['nid'];//get node ID
							$scheduleId = $schedule['id'];//get schedule id
							//echo "$nid - $scheduleId\n";
							//Determine the type of execution rule
							if(($schedule['isON'] != "-") && ($schedule['level'] == "-")){
								$isOn = "False";
								//Socket
								if($schedule['isON'] == "On")
									$isOn = "True";
								
								mysql_query("UPDATE `_nodes_` SET `isON`= '$isOn' WHERE `node_id` = '$nid'");
								
							}else if(($schedule['isON'] == "-") && ($schedule['level'] != "-")){
								//Dimmer
								$level = $schedule['level'];
								mysql_query("UPDATE `_nodes_` SET `level`= '$level' WHERE `node_id` = '$nid'");
							}
							else{
								//Controller
								//Do nothing
							}
							
							//deactivate schedule
							mysql_query("UPDATE `schedulesOnce` SET `active`= '0' WHERE `id` = '$scheduleId'");
							
							//remove schedule if 'remove' flag is set
							if($schedule['remove'] == '1'){
								mysql_query("DELETE FROM `schedulesOnce` WHERE `id` = '$scheduleId'");
								//echo "remove??? $scheduleId";
							}
							//echo "\n$timeHHmil:$timeMM $date\n$today\n$nid - $scheduleId\n";
						}

					}

				}

			}
		}
	}
	
	//checkRules
	public function checkRules(){
		//execute rules from low to high priority/index
		$rules = mysql_query("SELECT * FROM  `rules` ORDER BY  `rules`.`index` DESC ");
		
		while($rule = mysql_fetch_array($rules)){
		
			$if_nid = $rule['if_nid'];
			$if_isOn = $rule['if_isON'];
			$if_minLevel = $rule['if_minLevel'];
			$if_maxLevel = $rule['if_maxLevel'];

			$then_nid = $rule['then_nid'];
			$then_isOn = $rule['then_isON'];
			$then_Level = $rule['then_Level'];
			
			
			//IF CASES: 
			//1) if Socket => ($if_isOn != "-") && ($if_minLevel == "-") && ($if_maxLevel == "-")
			//2) if Dimmer => ($if_isOn == "-") && ($if_minLevel != "-") && ($if_maxLevel != "-")
			//3) if Controller => ($if_isOn == "-") && ($if_minLevel == "-") && ($if_maxLevel == "-")
			
			if(($if_isOn != "-") && ($if_minLevel == "-") && ($if_maxLevel == "-")){
				
				//check if node_id isON status match
				$if_node = mysql_query("SELECT * FROM nodes WHERE node_id = '$if_nid'");
				$if_node = mysql_fetch_array($if_node);
				if($if_node['isON'] == $if_isOn){
					//Execute the Rule
					//Determine the type of execution rule
					if(($then_isOn != "-") && ($then_Level == "-")){
						//Socket
						mysql_query("UPDATE `_nodes_` SET `isON`= '$then_isOn' WHERE `node_id` = '$then_nid'");
						
					}else if(($then_isOn == "-") && ($then_Level != "-")){
						//Dimmer
						mysql_query("UPDATE `_nodes_` SET `level`= '$then_Level' WHERE `node_id` = '$then_nid'");
					}
					else{
						//Controller
						//Do nothing
					}
				}
				else{
					//Do nothing
				}
			}
			else if(($if_isOn == "-") && ($if_minLevel != "-") && ($if_maxLevel != "-")){
				
				//check if node_id Level range match
				$if_node = mysql_query("SELECT * FROM nodes WHERE node_id = '$if_nid'");
				$if_node = mysql_fetch_array($if_node);
				if(($if_node['level'] >= $if_minLevel) && ($if_node['level'] <= $if_maxLevel)){
					//Execute the Rule
					//Determine the type of execution rule
					if(($then_isOn != "-") && ($then_Level == "-")){
						//Socket
						mysql_query("UPDATE `_nodes_` SET `isON`= '$then_isOn' WHERE `node_id` = '$then_nid'");
						
					}else if(($then_isOn == "-") && ($then_Level != "-")){
						//Dimmer
						mysql_query("UPDATE `_nodes_` SET `level`= '$then_Level' WHERE `node_id` = '$then_nid'");
					}
					else{
						//Controller
						//Do nothing
					}
				}
				else{
					//Do nothing
				}
			}
			else{
				//do nothing if controller
			}
			
			//THEN CASES: 
			//1) if Socket => ($then_isOn != "-") && ($then_Level == "-")
			//2) if Dimmer => ($then_isOn == "-") && ($then_Level != "-")
			//3) if Controller => ($then_isOn == "-") && ($then_Level == "-")
			
		}
	}
	
	
	public function getXMLData($node_id){
		
		//get XML file name
		$file_name = "file is missing";
		//file located 1 directory above the current location => '../'
		if ($handle = opendir("/var/www/")) {
			while (false !== ($entry = readdir($handle))) {
				if ((strpos($entry, 'zwcfg_') !== false) && (strpos($entry, '.xml') !== false)){
					$file_name = $entry;
				}
			}
			closedir($handle);
		}
		
		//load XML
		$nodes = simplexml_load_file($file_name);
		
		$home_id = $nodes['home_id']; //get home id
		$manufacturerID = "-";
		$productType = "-";
		$productID = "-";
		
		$homeid_manifactureid = $home_id . " " . $manufacturerID . " " . $productType . " " . $productID;
		
		foreach ($nodes as $node){
			if($node['id'] == $node_id){
				if($node->Manufacturer['id'] != ""){
					$manufacturerID = $node->Manufacturer['id'];
					$productType = $node->Manufacturer->Product['type'];
					$productID = $node->Manufacturer->Product['id'];
					$homeid_manifactureid = $home_id . " " . $manufacturerID . " " . $productType . " " . $productID;
				}
			}
		}

		return $homeid_manifactureid;
	}
	
	public function getXML_Home_id(){
		
		//get XML file name
		$stack = array("FILE_NOT_FOUND", "FILE_NOT_FOUND");
		//file located 1 directory above the current location => '../'
		if ($handle = opendir("/var/www/")) {
			while (false !== ($entry = readdir($handle))) {
				if ((strpos($entry, 'zwcfg_') !== false) && (strpos($entry, '.xml') !== false)){
					//load XML
					$nodes = simplexml_load_file("/var/www/$entry");
					$home_id = $nodes['home_id']; //get home id
					$md5 = md5_file("/var/www/$entry");
					array_push($stack, $home_id, $md5);
				}
			}
			closedir($handle);
		}
		return $stack;
	}
	
	public function getManufacturerSpecificXML($manufacturer_id, $product_type_id, $product_id){

		$stack = array("FILE_NOT_FOUND", "FILE_NOT_FOUND");
		
		if (file_exists("/var/www/py-openzwave/openzwave/config/manufacturer_specific.xml")){	
			
			array_push($stack, "NOT FOUND", "NOT FOUND");
			$PRODUCT_EXIST = FALSE;
			$PRODUCT_PUSH = FALSE;
			$manufactures = simplexml_load_file("/var/www/py-openzwave/openzwave/config/manufacturer_specific.xml");
			foreach ($manufactures as $manufacturer){
				if($manufacturer['id'] == $manufacturer_id){ // && ($manufacturer->Product['type'] == $product_type_id) && ($manufacturer->Product['id'] == $product_id)
					array_push($stack, $manufacturer['name']);
					foreach($manufacturer as $product){
						$PRODUCT_EXIST = TRUE;
						if(($product['type'] == $product_type_id) && ($product['id'] == $product_id)){
							array_push($stack, $product['name']);
							$PRODUCT_PUSH = TRUE;
						}
					}
					if(($PRODUCT_EXIST == TRUE) && ($PRODUCT_PUSH == FALSE))
						array_push($stack, "NOT FOUND");
				}
			}
			return $stack;
		} else {
			return $stack;
		}
	}


	public function getCommandClassesXML($node_id){

		$stack = array();
		
		//get XML file name
		$file_name = "file is missing";
		//file located 1 directory above the current location => '../'
		if ($handle = opendir("/var/www/")) {
			while (false !== ($entry = readdir($handle))) {
				if ((strpos($entry, 'zwcfg_') !== false) && (strpos($entry, '.xml') !== false)){
					$file_name = $entry;
				}
			}
			closedir($handle);
		}
		
		//mysql_query("INSERT INTO `command_classes`(`node_id`, `id`, `name`) VALUES ('1', '2', 'name')");
		if($file_name != "file is missing"){
			//load XML
			$nodes = simplexml_load_file($file_name);
			
			foreach ($nodes as $node){
				if($node['id'] == $node_id){
					foreach ($node->CommandClasses->CommandClass as $class){
						$id = $class['id'];
						$name = $class['name'];
						array_push($stack, $node_id, $id, $name);
						//echo $stack[0] . "-" . $stack[1] . "-" . $stack[2] . "\n";
					}
					
				}
			}

		}
		return $stack;
		
	}
	
	public function getPortNumber($call_type){
		
		// ' 2>&1' => even when a result is an error, it returns an error string which would contain original request ('/dev/ttyUSB*')
		// Therefore, if '/dev/ttyUSB*' found => USB port was not found => return 'Adapter Not Found'
		$output = shell_exec('ls -l /dev/ttyUSB*'.' 2>&1'); 
		$usb_not_found = strpos($output,'USB*');
		//echo $usb_not_found;
		
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
	
	public function removeXML(){
		$xml_remove_feedback = "Remove Failed. File Not Found.";
		if ($handle = opendir("/var/www/")) {
			while (false !== ($entry = readdir($handle))) {
				if ((strpos($entry, 'zwcfg_') !== false) && (strpos($entry, '.xml') !== false)){
					unlink ("/var/www/$entry");
					$xml_remove_feedback = "$entry has been Removed."; 
				}
			}
			closedir($handle);
		}
		return $xml_remove_feedback;
	}
	
	
}
?>









