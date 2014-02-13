<?php
	$time_start = microtime(true);
	define("appHome","slup_php/");
	require_once appHome."config/startup.php";
	echo  (microtime(true) - $time_start). ' seconds\r\n';
?>