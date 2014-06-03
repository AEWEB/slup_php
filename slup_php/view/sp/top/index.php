<?php 
	require_once AppConfig::getStringPath()."task.php";
?>
<div id="basic" data-role="page" data-title="<?php print(AppConfigRunnable::formalName); ?>" data-url="/">
  <div data-role="header">
  	<h1><?php print(AppConfigRunnable::formalName);?></h1>
  	<div data-role="navbar" data-iconpos="left" >
		<ul>
			<li><a href="#" onclick="task.init(); return false;" data-role="button"><?php print(TaskString::appName);	?></a>  </li>
			<!--  
			<li><a href="<?php	//print($controller->getActionUrl(LearningResource::appHome,null));	?>" data-role="button"><?php //print(LearningResource::appName);	?></a></li>
	 		<li><a href="#" onclick="negotiator.init(); return false;" data-role="button"><?php //print(NegotiatorResource::appName);	?></a>  </li>
			-->
		</ul>
	</div>
  </div>
  <div id="run_exe" data-role="content">
  	<h1>
		HELLO SLC!!!
	</h1>
  </div>
  <div data-role="footer">
	 <?php 
		require $controller->getAppMenu();
	?>
  </div>
</div>