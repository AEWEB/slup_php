<div id="run_exe">
	<?php 
		require_once $controller->getViewPath()."commonHeader.php";
	?>
	<div class="lf_container" style="width:100%;font-size: 23px;">
		<p style="text-align: left;line-height: 50px;">
			画像フォルダ
		</p>
	</div>
	<div class="contents_base">
		<p class="error_message"><?php print(Model::getErrorMessage());	?></p>
		<?php print($controller->getActionForm("imageUpload",null,"POST","enctype='multipart/form-data'",$controller->getImageForm()));?>
			<table class="designTable">
				<tr>
					<th colspan="2" style="text-align:left">画像の追加</th>
				</tr>
				<tr>
					<td>1<?php print(CommonResources::big_colon);?><input type="file" name="<?php print(ImageController::image_name_1);?>"></td>
					<td><?php print(ModelResource::slimage_title.CommonResources::big_colon.$controller->getImageForm()->get(ImageForm::parseFormName(ImageForm::image_1)));?></td>
				</tr>
				<tr>
					<td>2<?php print(CommonResources::big_colon);?><input type="file" name="<?php print(ImageController::image_name_2);?>"></td>
					<td><?php print(ModelResource::slimage_title.CommonResources::big_colon.$controller->getImageForm()->get(ImageForm::parseFormName(ImageForm::image_2)));?></td>
				</tr>
				<tr>
					<td>3<?php print(CommonResources::big_colon);?><input type="file" name="<?php print(ImageController::image_name_3);?>"></td>
					<td><?php print(ModelResource::slimage_title.CommonResources::big_colon.$controller->getImageForm()->get(ImageForm::parseFormName(ImageForm::image_3)));?></td>
				</tr>
				<tr>
					<td colspan="2" style="text-align:center"><input type="submit" value="<?php print(CommonResources::dataRegist);?>" class="medium gray awesome"></td>
				</tr>
			</table>
    	</form>
    	<table class="designTable">
    		<tr>
    		<?php 
    			$tr=0;
    			$imageList=$controller->getImageList();
    			for($i=0;$i<count($imageList);$i++){
    				?>
    				<td>
    					<img border="0" src="<?php print(Slimage::getSrc($imageList[$i]));?>" width="100px" height="100px" ><br/>
    					<?php print($imageList[$i]->get(Slimage::title));	?><a href="<?php print(HtmlHelper::getActionUrl("image","delete")."?id=".$imageList[$i]->get(Slimage::id)); ?>"><img src="<?php print(AppConfig::getImagePath()."trash.png");?>" ></a>
    				</td>
    				<?php
    				if($tr===7){
    					print("</tr><tr>");
    					$tr=0;
    				}else{
    					$close="";
    					$tr++;
    				}
    			}
    			
    		?>
    		</tr>
    	</table>
    	<div style="text-align: center;">
			<?php 
				$buttonCount=$controller->getImageCount()/ImageController::imageShowCount;
				for($i=0;$i<$buttonCount;$i++){
					$limitStart=$i*ImageController::imageShowCount;
					?>
					<a href="<?php print(HtmlHelper::getActionUrl("image",null)."?page=".$limitStart); ?>" class="small gray awesome"><?php 
						print(($i+1));
					?></a>
					<?php
				}
					?>
		</div>
	</div>
	<?php 
		require_once $controller->getViewPath()."commonFooter.php";
	?>
</div>