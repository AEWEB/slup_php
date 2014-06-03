<div id="basic" data-role="page" data-title="<?php print(AppConfigRunnable::formalName); ?>">
  <div data-role="header">
  	<h1><?php print(ModelResource::sl_user_id);	?>再通知</h1>
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
  	<?php print($controller->getActionForm("reissueId","reissueId","POST","",$controller->getUser()));?>
		<div data-role="fieldcontain">
  			<label for="basic_mail"><?php 
  				print(ModelResource::sl_user_m_id);
  			?>を入力して下さい。</label>
  			<p>
				<?php	print($controller->getUser()->get(Sl_user::parseFormName(Sl_user::mid))); ?>
			</p>
		</div>
		<input type="submit" value="<?php print(ModelResource::sl_user_id);	?>再通知">
	</form>
	<?php 
		require_once $controller->getViewPath()."commonFooter.php";
	?>
  </div>
  <div data-role="footer">
  	<a href="<?php 
	 			print(HtmlHelper::getActionUrl("top",null));
	 		?>" data-role="button">ログイン</a>
	 <a href="<?php 
	 	print(HtmlHelper::getActionUrl("top","reissuePassword")); ?>" data-role="button"><?php 
	 		print(ModelResource::sl_user_password);
	 	?>再設定</a>
    <?php 
		require $controller->getAppMenu();
	?>
  </div>
</div>