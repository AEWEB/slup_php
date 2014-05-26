<div id="run_exe">
	<div id="login_main">
		<div class="login_table_left">
			<div class="lf_container" style="width: 99%;margin-top: 70px;">
				<p style="font-size:35px;text-align: right;color: #444444;">
					<?php print(AppConfigRunnable::formalName);?>
				</p>
				<p style="font-size:23px;line-height:25px;text-align: right;"><br/>
					リアルをソーシャル<span style="color: #bb3333">コーディネート</span>
				</p>
			</div>
		</div>
		<div class="login_table_right">
			<div class="contents_base">
				<p class="error_message">
				<?php print(Model::getErrorMessage());?>
				</p>
				<?php print($controller->getActionForm("login_form",null,"POST","",$controller->getUser()));?>
				<table class="designTable">
						<tr>
							<th>ID</th>
							<td>
								<?php	print($controller->getUser()->get(Sl_user::parseFormName(Sl_user::id))); ?>
							</td>
						</tr>
						<tr>
							<th style="font-size:10px;">パスワード</th>
							<td>
								<?php	print($controller->getUser()->get(Sl_user::parseFormName(Sl_user::password))); ?>
							</td>
						</tr>
						<tr>
							<td colspan=2>
								<input type="submit" value="Login" class="medium gray awesome">
								<a class="pickup" href="<?php print(HtmlHelper::getActionUrl("top","reissueId")); ?>">ID・パスワードを忘れてしまった場合</a>
							<br/>サンプルユーザーでログインできます。<br/>
							iD:sample PW:test
							</td>
						</tr>
						<tr>
							<td colspan=2 style="padding-top: 5px;padding-bottom: 10px;">
								<a class="small gray awesome" href="<?php	print(HtmlHelper::getActionUrl("top","twitterAuth"));	?>">
									<image src="<?php print(AppConfig::getImagePath()."twitter-bird.png");	?>" width="30px" height="30px" style="vertical-align:top;" />
								</a>
								<a class="small gray awesome" href="<?php	print(HtmlHelper::getActionUrl("top", "facebookAuth"));	?>">
									<image src="<?php print(AppConfig::getImagePath()."facebook-logo.jpg");	?>" width="30px" height="30px" style="vertical-align:top;" />
								</a>
							</td>
						</tr>
					</table>
				</form>
			<?php print($controller->getActionForm("register_form","register","POST","",$controller->getUser(),"20 minute"));?>
				<table class="designTable">
					<tr>
						<th colspan="2" style="text-align: left;">
							<p>メールアドレスでアカウント作成しましょう！</p>
							<?php	print($controller->getUser()->get(Sl_user::parseFormName(Sl_user::mid))); ?>
							<input type="submit" value="新規登録" class="medium blue_e awesome" style=" background-image:none;">
						</th>
					</tr>
				</table>
			</form>
			</div>
		</div>
	</div>
	<div id="login_footer">
		<table class="designTable">
			<tr>
				<th style="text-align:left;" colspan=2>
					提供中のコンテンツ
				</th>
			</tr>
			<tr>
				<td style="text-align:right;width:15%;">
					<a class="pickup" href="<?php	//print(HtmlHelper::getActionUrl(LearningResource::appHome,null));	?>">
					<?php //print(LearningResource::appName);	?>
					</a>
				</td>
				<td style="text-align:left;width:80%;">
					<?php //print(LearningResource::appDescription);	?>
				</td>
			</tr>
			<tr>
				<td style="text-align:right;width:15%;">
					<a class="pickup" href="<?php	//print(HtmlHelper::getActionUrl(NegotiatorResource::appHome,null));	?>">
					<?php //print(NegotiatorResource::appName);	?>
					</a>
				</td>
				<td style="text-align:left;width:80%;">
					<?php //print(NegotiatorResource::appDescription);	?>
				</td>
			</tr>
		</table>
	</div>
</div>