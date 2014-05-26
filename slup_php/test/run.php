<?php
	define("appHome","../");
	require_once appHome."config/testConfig.php";	
	$test=new LF_test(array("DatabaseParameter","HtmlHelper"
		,"MySQLDriver",
	"Model",
	"Controller","TopController"
	));
	$test->outputInfo();
?>