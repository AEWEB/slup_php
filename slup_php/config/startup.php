<?php
	require_once appHome.'config/config.php';
	require_once AppConfig::getResourcePath()."resource.php";
	require_once AppConfig::getLibPath().'slup.php';
	require_once AppConfig::getConfigPath()."database.php";
	require_once AppConfig::getAppPath().'applicationBase.php';
	$appName=Controller::getControllerName();
	try{
		include_once AppConfig::getAppPath().$appName.".php";
		$controllerName=ucfirst($appName).AppConfig::addControllerName;
		$controller=new $controllerName();
	}catch (Exception $e){
		exit($e);
	}
	require_once $controller->getOutput();
?>