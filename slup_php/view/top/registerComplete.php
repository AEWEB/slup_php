<div id="run_exe">
	<?php 
	require_once $controller->getViewPath()."commonHeader.php";
	?>
	<div class="contents_base" style="margin-top: 50px;">
	<div class="lf_container" style="width:100%;font-size: 23px;">
		<p style="text-align: left;line-height: 60px;">
		下記項目を入力して登録完了です！
		</p>
	</div>
	<p class="error_message">
		<?php
			print(Model::getErrorMessage());
		?>
	</p>
	<?php print($controller->getActionForm("login_form","registerComplete","POST","",$controller->getUser()));?>
		<table class="designTable">
			<tr>
				<th><?php print(ModelResource::sl_user_id);?>
				</th>
				<td><?php	print($controller->getUser()->get(Sl_user::parseFormName(Sl_user::id))); ?><br/>
				<span style="font-size:10px;" class="error_message">※半角英数字、アンダーバー、ハイフンが使用できます。</span></td>
			</tr>
			<tr>
				<th><?php print(ModelResource::sl_user_password);?>
				<td><?php	print($controller->getUser()->get(Sl_user::parseFormName(Sl_user::password))); ?></td>
			</tr>
			<tr>
				<th><?php print(ModelResource::sl_user_passwordConfirmation);?>
				<td><?php	print($controller->getUser()->get(Sl_user::parseFormName(Sl_user::passwordConfirmation))); ?></td>
			</tr>
			<tr>
				<th><?php print(ModelResource::sl_user_m_id);?></th>
				<td><?php	print($controller->getUser()->get(Sl_user::mid)); ?></td>
			</tr>
			<tr>
				<td colspan="2" style="text-align:center;">
					<a class="pickup" href="<?php print(HtmlHelper::getActionUrl("top","terms")); ?>" target="_blank">利用規約</a>
						<?php
							print($controller->getUser()->get(Sl_user::parseFormName(Sl_user::consentCheck)));	
						?>
					<input type="submit" value="登録" class="medium gray awesome" style=" background-image:none;">
				</td>
			</tr>
		</table>
	</form>
	</div>
	<?php 
		require_once $controller->getViewPath()."commonFooter.php";
	?>
</div>