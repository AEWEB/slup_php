<div id="basic" data-role="page" data-title="<?php	print(AppConfigRunnable::formalName);	?>">
  <div data-role="header">
    <h1>下記項目を入力して登録完了です！</h1>
  </div>
  <div data-role="content">
  	<p>
		<?php
			print(Model::getErrorMessage());
		?>
	</p>
	<?php print($controller->getActionForm("registerComplete","registerComplete","POST","",$controller->getUser()));?>
		<div data-role="fieldcontain">
     		<p><label for="basic_id"><?php print(ModelResource::sl_user_id);?></label></p>
      		<?php	print($controller->getUser()->get(Sl_user::parseFormName(Sl_user::id))); ?>
        </div>
		<div data-role="fieldcontain">
			<p><label for="basic_pass"><?php print(ModelResource::sl_user_password);?></label></p>
        	<?php	print($controller->getUser()->get(Sl_user::parseFormName(Sl_user::password))); ?>
        </div>
        <div data-role="fieldcontain">
			<p><label for="basic_passConfirmation"><?php print(ModelResource::sl_user_passwordConfirmation);?></label></p>
        	<?php	print($controller->getUser()->get(Sl_user::parseFormName(Sl_user::passwordConfirmation))); ?>
        </div>
		<div data-role="fieldcontain">
			<p><label for="basic_mailAdd"><?php print(ModelResource::sl_user_m_id);?></label></p>
			<?php	print($controller->getUser()->get(Sl_user::mid)); ?>
		 </div>
		<div data-role="fieldcontain">
			<p><a class="pickup" href="<?php print(HtmlHelper::getActionUrl("top","terms"));	?>">利用規約</a>のチェック</p>
			<?php
				print($controller->getUser()->get(Sl_user::parseFormName(Sl_user::consentCheck)));	
			?>
			<label for="label_<?php print(Sl_user::consentCheck);	?>">
				同意する
			</label>	
		</div>
		<div data-role="fieldcontain">
			<input type="submit" value="登録" style="background-image:none;">
		</div>
	</form>
  </div>
  <div data-role="footer">
    <?php 
		require $controller->getAppMenu();
	?>
  </div>
</div>