<?php
	try{
		$view=$controller->run();
		require_once $view;
	}catch (Exception $e){
		$controller->errorOutput($e);
	}
?>