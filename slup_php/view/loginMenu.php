<div id="global_menu_area">
	 <ul id="contents_navi">
		<li>
		 	<a href="<?php 
	 			print(HtmlHelper::getActionUrl("top",null));
	 			?>" >TOP</a>
	 	</li>
	 	<li>
	 		<a href="#">メニュー1</a>
	 	</li>
	 	<li>
	 		<a href="<?php 
	 			print(HtmlHelper::getActionUrl("image",null));
	 			?>">画像フォルダ</a>
	 	</li>
	 	<li>
	 		<a href="<?php
	 			print(HtmlHelper::getActionUrl("top","showHelp"));
	 		?>">ヘルプ</a>
	 	</li>
	 	<li>
	 		<a href="<?php
	 			print(HtmlHelper::getActionUrl("top","logout"));
	 		?>">ログアウト</a>
	 	</li>
	 	<li style="padding-top: 5px;">
			<image src="<?php print($controller->getUser()->get(Sl_user::imageurl));	?>" width="28px" height="26px" style="vertical-align:top;"/>
		</li>
	 </ul>
</div>