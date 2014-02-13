<?php
	define("appHome","../");
	require_once appHome."config/testConfig.php";	
	$test=new LF_test(array("DatabaseParameter"
	,"MySQLDriver","Model"
	,"Controller"//,"CustomTag_test"
	));
	$test->outputInfo();
?>