<div id="run_exe">
	<?php 
		require_once $controller->getViewPath()."commonHeader.php";
	?>
	<div class="lf_container" style="width:100%;font-size: 23px;">
		<p style="text-align: left;line-height: 50px;">
		<?php print(ModelResource::sl_user_password);	?>再設定
		</p>
	</div>
	<p class="usual_font" style="margin-top: 5px;"><?php print(ModelResource::sl_user_m_id);	?>を入力して下さい。</p>
	<div class="contents_base">
		<?php
			require_once $controller->getViewPath()."/top/reSetupView.php";
		?>
		<p class="error_message">
			<?php
				print(Model::getErrorMessage());
			?>
		</p>
		<?php print($controller->getActionForm("login_form","reissuePassword","POST","",$controller->getUser()));?>
			<table class="designTable">
				<tr>
					<th><?php 
						print(ModelResource::sl_user_m_id);
					?></th>
					<td><?php	print($controller->getUser()->get(Sl_user::parseFormName(Sl_user::mid))); ?></td>
				</tr>
				<tr>
					<td colspan="2" style="text-align:center;">
						<input type="submit" value="送信" class="medium blue_e awesome" style=" background-image:none;">
					</td>
				</tr>
			</table>
		</form>
	</div>
	<?php 
		require_once $controller->getViewPath()."commonFooter.php";
	?>
</div>