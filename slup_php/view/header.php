<!doctype html>
<html>
<head>
<meta charset="UTF-8" />
<title><?php print($controller->getTitle());?></title>
<script type='text/javascript'>
var error_url="<?php	print($controller->getJsErrorUrl());	?>";
var global_path="<?php	print($controller->getJsAppUrl());	?>";
</script>
<script src="<?php   print(AppConfig::getResourcePathFromBrowser());    ?>js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="<?php   print(AppConfig::getResourcePathFromBrowser());    ?>js/prettyLoader/js/jquery.prettyLoader.js"></script>
<link rel="stylesheet" href="<?php   print(AppConfig::getResourcePathFromBrowser());    ?>js/prettyLoader/css/prettyLoader.css" type="text/css" media="screen" />
<script type='text/javascript' src='<?php   print(AppConfig::getResourcePathFromBrowser());    ?>js/ajax.js'></script>
<script type='text/javascript' src='<?php   print(AppConfig::getResourcePathFromBrowser());    ?>js/basic_function.js'></script>
<script type='text/javascript' src='<?php   print(AppConfig::getResourcePathFromBrowser());    ?>js/alerts/jquery.alerts.js'></script> 
<link rel='stylesheet' href='<?php   print(AppConfig::getResourcePathFromBrowser());    ?>js/alerts/jquery.alerts.css' type='text/css' media='screen'  />
<script type='text/javascript' src='<?php   print(AppConfig::getResourcePathFromBrowser());    ?>js/tooltip/script.js'></script>	
<link rel='stylesheet' href='<?php   print(AppConfig::getResourcePathFromBrowser());    ?>js/tooltip/style.css' type='text/css' />
<script type='text/javascript'>
	var access=new AccessSupport();
	$(function() {
		$.prettyLoader();
    	<?php
    	//	$control->printJsJquery();
    	?>
	});
</script>
<?php	$controller->printJs();?>
<link rel='stylesheet' href='<?php   print(AppConfig::getResourcePathFromBrowser());    ?>css/app.css' type='text/css' />
<?php 	$controller->printCss();?>
</head>
<body>
<?php 
	require_once $controller->getAppMenu();
?>
<div id='contents_area'>