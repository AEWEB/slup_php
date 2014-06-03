<?php
	try{
		$view=$controller->run();
		require_once $controller->getHeader();
		require_once $view;
		require_once $controller->getFooter();
	}catch (Exception $e){
		$controller->errorOutput($e);
	}
?>