<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title><?php 
print($controller->getTitle());
?></title>
<script type='text/javascript'>
var error_url="<?php	print($controller->getJsErrorUrl());	?>";
var global_path="<?php	print($controller->getJsAppUrl());	?>";
</script>
<link rel="stylesheet" href="<?php   print(AppConfig::getResourcePathFromBrowser());    ?>/css/jquery.mobile-1.3.1.min.css" />
<script src="<?php   print(AppConfig::getResourcePathFromBrowser());    ?>js/jquery-1.9.1.js"></script>
<script src="<?php   print(AppConfig::getResourcePathFromBrowser());    ?>js/jquery.mobile-1.3.1.min.js"></script>
<script type="text/javascript" src="<?php   print(AppConfig::getResourcePathFromBrowser());    ?>js/prettyLoader/js/jquery.prettyLoader.js"></script>
<script type='text/javascript' src='<?php   print(AppConfig::getResourcePathFromBrowser());    ?>js/ajax.js'></script>
<script type='text/javascript' src='<?php   print(AppConfig::getResourcePathFromBrowser());    ?>js/basic_function.js'></script>
<script type='text/javascript' src='<?php   print(AppConfig::getResourcePathFromBrowser());    ?>js/alerts/jquery.alerts.js'></script> 
<link rel='stylesheet' href='<?php   print(AppConfig::getResourcePathFromBrowser());    ?>js/alerts/jquery.alerts.css' type='text/css' media='screen'  />	
<script type='text/javascript'>
	var access=new AccessSupport_mobile();
	$(function() {
		$.prettyLoader();
    	<?php
    	//	$controller->printJsJquery();
    	?>
	});
</script>
<?php	$controller->printJs();?>
<link rel='stylesheet' href='<?php   print(AppConfig::getResourcePathFromBrowser());    ?>css/lf_mobile.css' type='text/css' />
</head>
<script language="JavaScript">
$.mobile.ajaxEnabled = false;
</script>
<body>