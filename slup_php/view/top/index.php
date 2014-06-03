<?php 
	require_once AppConfig::getStringPath()."task.php";
	require_once AppConfig::getStringPath()."learning.php";
?>
<a href="#" onclick="task.init('0','desc'); return false;" class="small gray awesome"><?php print(TaskString::appName);	?></a>
<a class="small blue_e awesome" href="<?php 
	print(HtmlHelper::getActionUrl(LearningString::appHome, null));
?>"><?php print(LearningString::appName);	?></a>
<br/>
<div id="run_exe" class="contents_base">
	<h1>
		HELLO <?php print(AppConfigRunnable::formalName);?>!
	</h1>
</div>