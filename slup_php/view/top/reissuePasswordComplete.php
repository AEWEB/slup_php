<div id="run_exe">
	<?php 
		require_once $controller->getViewPath()."commonHeader.php";
	?>
	<div class="lf_container" style="width:100%;font-size: 23px;">
		<p style="text-align: left;line-height: 50px;">
		<?php print(ModelResource::sl_user_password);	?>再設定
		</p>
	</div>
	<p class="usual_font" style="margin-top: 5px;"><?php print(ModelResource::sl_user_password);	?>を入力して下さい。</p>
	<div class="contents_base">
		<p class="error_message">
			<?php
				print(Model::getErrorMessage());
			?>
		</p>
		<?php print($controller->getActionForm("reissuePasswordComplete","reissuePasswordComplete","POST","",$controller->getUser()));?>
			<table class="designTable">
				<tr>
					<th><?php print(ModelResource::sl_user_password);?></th>
					<td>
						<?php	print($controller->getUser()->get(Sl_user::parseFormName(Sl_user::password))); ?>
					</td>
				</tr>
				<tr>
					<th><?php print(ModelResource::sl_user_passwordConfirmation);?></th>
					<td>
						<?php	print($controller->getUser()->get(Sl_user::parseFormName(Sl_user::passwordConfirmation))); ?>
					</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align:center;">
						<input type="submit" value="変更" class="medium gray awesome">
					</td>
				</tr>
			</table>
		</form>
	</div>
	<?php 
		require_once $controller->getViewPath()."commonFooter.php";
	?>
</div>