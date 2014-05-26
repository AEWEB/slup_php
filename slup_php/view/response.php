<div id="run_exe">
	<?php 
	require_once $controller->getViewPath()."commonHeader.php";
	?>
	<div class="contents_base" style="margin-top: 50px;">
	<p class="error_message" >
		<?php 
			print(Model::getErrorMessage());
		?>
	</p>
	</div>
	<?php 
		require_once $controller->getViewPath()."commonFooter.php";
	?>
</div>