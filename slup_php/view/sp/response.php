<div id="basic" data-role="page" data-title="<?php print(AppConfigRunnable::formalName);?>">
  <div data-role="header">
    <h1><?php print(AppConfigRunnable::formalName);	?></h1>
  </div>
  <div data-role="content">
  	<?php 
		require_once $controller->getViewPath()."commonHeader.php";
	?>
  	<?php 
  		print(Model::getErrorMessage());
  	?>
  	<?php 
		require_once $controller->getViewPath()."commonFooter.php";
	?>
  </div>
  <div data-role="footer">
    <?php 
		require $controller->getAppMenu();
	?>
  </div>
</div>