<div id="basic" data-role="page">
  <div data-role="header">
   <h1><?php print(AppConfigRunnable::formalName);?></h1>
  </div>
  <div data-role="content">
  	<div>
  	<?php
		print(Model::getErrorMessage());
	?>
  	</div>
  	<div data-role="fieldcontain" class="lf_container">
  		<p line-height:25px;text-align: left;">
    		リアルをソーシャルで<span style="color: #bb3333">楽しくコーディネート！</span>
    	</p>
    </div>
  	<?php print($controller->getActionForm("login_form",null,"POST","",$controller->getUser()));?>
		<div data-role="fieldcontain">
     	<p><label for="basic_id">ID</label></p>
      		<?php	print($controller->getUser()->get(Sl_user::parseFormName(Sl_user::id))); ?>
        </div>
		<div data-role="fieldcontain">
		<p><label for="basic_pass">パスワード</label></p>
        	<?php	print($controller->getUser()->get(Sl_user::parseFormName(Sl_user::password))); ?>
        </div>
        <div data-role="fieldcontain">
        	<input type="submit" value="ログイン">
        </div>
	</form>
	<div data-role="fieldcontain">
		<a href="<?php	print(HtmlHelper::getActionUrl("top","twitterAuth"));	?>">
			<image src="<?php print(AppConfig::getImagePath()."twitter-bird.png");	?>" width="45px" height="45px" style="vertical-align:top;" />
		</a>
		<a href="<?php	print(HtmlHelper::getActionUrl("top","facebookAuth"));	?>">
			<image src="<?php print(AppConfig::getImagePath()."facebook-logo.jpg");	?>" width="45px" height="45px" style="vertical-align:top;" />
		</a>
	</div>
	<div data-role="fieldcontain" style="margin-top:30px;">
		<ul data-role="listview" data-inset="true" >
			<li data-role="list-divider">
				提供中のコンテンツ
			</li>
			<li>
				テスト
			</li>
			<!--
			<li><a href="<?php	//print(HtmlHelper::getActionUrl(LearningResource::appHome,null));	?>"><?php //print(LearningResource::appName);	?><br/>
				<?php //print(LearningResource::appDescription);	?></a>
			</li>
			<li><a href="<?php	//print(HtmlHelper::getActionUrl(NegotiatorResource::appHome,null));	?>"><?php //print(NegotiatorResource::appName);	?><br/>
				<?php //print(NegotiatorResource::appDescription);	?></a>
			</li>
			  -->
		</ul>
	</div>
  </div>
  <div data-role="footer">
  	<a href="#basic" data-role="button">ログイン</a>
	<a href="#register" data-role="button">アカウント作成</a>
	<a href="<?php 
	 			print(HtmlHelper::getActionUrl("top","reissueId"));
	 		?>" data-role="button">ID・パスワードを忘れてしまった場合</a>
    <?php 
		require $controller->getAppMenu();
	?>
  </div>
</div>
<div id="register" data-role="page">
  <div data-role="header">
    <h1>メールアドレスでアカウント作成しましょう！</h1>
  </div>
  <div>
  	<?php
		print(Model::getErrorMessage());
	?>
  	</div>
  <div data-role="content">
  	<?php print($controller->getActionForm("register_form","register#register","POST","",$controller->getUser()));?>
  		<div data-role="fieldcontain">
  			<label for="basic_mail">メールアドレスを入力して下さい。</label>
  			<p>
  				<?php	print($controller->getUser()->get(Sl_user::parseFormName(Sl_user::mid))); ?>
			</p>
		</div>
		<input type="submit" value="新規登録">
	</form>
  </div>
  <div data-role="footer">
  	<a href="#basic" data-role="button">ログイン</a>
	<a href="#register" data-role="button">アカウント作成</a>
    <?php 
		require $controller->getAppMenu();
	?>
  </div>
</div>