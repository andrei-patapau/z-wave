<?php
//$DelFilePath = "../zwcfg_0x014d0d27.xml";
//if (file_exists($DelFilePath)) { unlink ($DelFilePath); }
if ($handle = opendir('../')) {
    while (false !== ($entry = readdir($handle))) {
        if ((strpos($entry, 'zwcfg_') !== false) && (strpos($entry, '.xml') !== false)){
			//Before removing file need to get it's data
			unlink ("../$entry");
			echo "\n$entry has been Removed"; 
		}
    }
    closedir($handle);
}
?>