<?php 
session_start();
session_destroy();
header("Location: /i/index.php");
?>