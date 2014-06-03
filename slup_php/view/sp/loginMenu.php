<div style="text-align:right;padding-top:5px;padding-right:20px;">
	<image src="<?php print($controller->getUser()->get(Sl_user::imageurl));	?>" width="28px" height="26px" style="vertical-align:top;"/>
</div>
 <div data-role="navbar" data-iconpos="left" >
	<ul>
		<li><a href="<?php 
	 			print(HtmlHelper::getActionUrl("top",null));
	 		?>" data-icon="gear">TOP</a></li>
	 	<li><a href="<?php 
	 			print(HtmlHelper::getActionUrl("image",null));
	 		?>" data-icon="gear">画像フォルダ</a></li>
	 	<li><a href="<?php 
	 			print(HtmlHelper::getActionUrl("top","showHelp"));
	 		?>" data-icon="gear">ヘルプ</a></li>
	 	<li><a href="<?php
	 		print(HtmlHelper::getActionUrl("top","logout"));
	 		?>">ログアウト</a></li>
	</ul>
</div>