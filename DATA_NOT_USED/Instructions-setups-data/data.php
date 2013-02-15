<?php include 'execute.php'; ?>
<?php

define("HTTP_HOST", "192.168.1.102");
define("REMOTE_PORT", 2222);

$data = new dataClass();

if(isset($_POST['action']) && !empty($_POST['action'])) {
    $action = $_POST['action'];
    switch($action) {
        case 'getRequest' : $data->getRequest(); break;
		case 'execPerlOFF' : $data->execPerlOFF(); break;
		case 'execPerlON' : $data->execPerlON(); break;
		case 'getPortNumber' : $data->getPortNumber(); break;
		case 'setPortPermissions' : $data->setPortPermissions(); break;
    }
}

class dataClass
{
	public function getRequest(){
		//$request = "http://" . $_SERVER['HTTP_HOST'] . ":" . $_SERVER['REMOTE_PORT'] . "/" . $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'];
		
		# Connect to the Web API using cURL.
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, 'http://192.168.1.102:2222/index.php?devname'); 
		curl_setopt($ch, CURLOPT_TIMEOUT, '3'); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$xmlstr = curl_exec($ch); 
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		curl_close($ch);
		echo "$ch";
	}

	public function getPortNumber($call_type){

		//$call_type = "fcall" | browser call
		$output = shell_exec('ls -l /dev/ttyUSB*'); 
		
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

	public function execPerlOFF(){
		$port = $this->getPortNumber("fcall");
		
		$shell = new PHPsh;
		ob_clean();//Clean (erase) the output buffer (does not destroy the output buffer)
		ob_start();//Turn on output buffering
		$shell->execCommand("./z-waver.pl $port switch 2 off", $OutputEscapeFlag);
		$value = ob_get_contents();//Return the contents of the output buffer
		ob_end_clean();//Clean (erase) the output buffer and turn off output buffering
		
		$status = "OFF";
		echo json_encode(array("output"=>$value,"status"=>$status));

	}

	public function execPerlON(){
		$port = $this->getPortNumber("fcall");
		
		$shell = new PHPsh;
		ob_clean();//Clean (erase) the output buffer (does not destroy the output buffer)
		ob_start();//Turn on output buffering
		$shell->execCommand("./z-waver.pl $port switch 2 on", $OutputEscapeFlag);
		$value = ob_get_contents();//Return the contents of the output buffer
		ob_end_clean();//Clean (erase) the output buffer and turn off output buffering

		$status = "ON";
		echo json_encode(array("output"=>$value,"status"=>$status));

	}
	
	public function setPortPermissions(){
		
		$shell = new PHPsh;
		ob_clean();//Clean (erase) the output buffer (does not destroy the output buffer)
		ob_start();//Turn on output buffering
		$shell->execCommand("sh /var/www/shell_file", $OutputEscapeFlag);
		$value = ob_get_contents();//Return the contents of the output buffer
		ob_end_clean();//Clean (erase) the output buffer and turn off output buffering

		echo $value;

	}
	
}

?>
