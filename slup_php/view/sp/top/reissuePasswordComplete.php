<div id="basic" data-role="page" data-title="<?php print(AppConfigRunnable::formalName); ?>">
  <div data-role="header">
   <h1><?php print(ModelResource::sl_user_password);	?>再設定</h1>
  </div>
  <div data-role="content">
  	<?php 
		require_once $controller->getViewPath()."commonHeader.php";
	?>
  	<div>
  		<?php
			print(Model::getErrorMessage());
		?>
  	</div>
 	<?php print($controller->getActionForm("reissuePasswordComplete","reissuePasswordComplete","POST","",$controller->getUser()));?>
		<div data-role="fieldcontain">
  			<div data-role="fieldcontain">
				<p><label for="basic_pass"><?php print(ModelResource::sl_user_password);	?></label></p>
        		<?php	print($controller->getUser()->get(Sl_user::parseFormName(Sl_user::password))); ?>
        	</div>
        	<div data-role="fieldcontain">
				<p><label for="basic_passConfirmation"><?php print(ModelResource::sl_user_passwordConfirmation);?></label></p>
        		<?php	print($controller->getUser()->get(Sl_user::parseFormName(Sl_user::passwordConfirmation))); ?>
        	</div>
		</div>
		<input type="submit" value="<?php print(ModelResource::sl_user_password);	?>再設定">
	</form>
	<?php 
		require_once $controller->getViewPath()."commonFooter.php";
	?>
  </div>
  <div data-role="footer">
  	<a href="<?php 
	 			print(HtmlHelper::getActionUrl("top",null));
	 		?>" data-role="button">ログイン</a>
    <?php 
		require $controller->getAppMenu();
	?>
  </div>
</div>