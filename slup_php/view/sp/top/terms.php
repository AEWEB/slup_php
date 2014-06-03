<div id="basic" data-role="page" data-title="<?php print(AppConfigRunnable::formalName); ?>">
  <div data-role="header">
    <h1>利用規約</h1>
  </div>
  <div data-role="content">
  	<?php 
  		require_once AppConfig::getResourcePath()."terms.php";
  	?>
  </div>
  <div data-role="footer">
  	<a href="<?php print(HtmlHelper::getActionUrl("top","registerComplete"));	?>" data-role="button">戻る</a>
    <?php 
		require $controller->getAppMenu();
	?>
  </div>
</div>