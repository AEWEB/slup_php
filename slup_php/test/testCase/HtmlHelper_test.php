<?php
	class HtmlHelper_test extends Lf_testCase{
		public function testText(){
			$this->getControl()->equals(htmlspecialchars("<input type=\"hidden\" name=\"".($name="test")."\" value=\"".($value="value")."\" ".
					($option="option").">",ENT_QUOTES,AppConfig::character), 
				htmlspecialchars(HtmlHelper::text($name, $value, array(ModelRunnable::formType=>"hidden",ModelRunnable::formIndexOption=>$option)),ENT_QUOTES,AppConfig::character));
			$this->getControl()->equals(htmlspecialchars("<input type=\"hidden\" name=\"".$name."\" value=\"".$value."\" ".
				CommonResources::nullCharacter.">",ENT_QUOTES,AppConfig::character), 
				htmlspecialchars(HtmlHelper::text($name, $value, array(ModelRunnable::formType=>"hidden")),ENT_QUOTES,AppConfig::character));
			$this->getControl()->equals(htmlspecialchars("<input type=\"text\" name=\"".$name."\" value=\"".$value.
				"\" size=\"4\" maxlength=\"4\"".CommonResources::nullCharacter." >",ENT_QUOTES,AppConfig::character),
					htmlspecialchars(HtmlHelper::text($name, $value, array(ModelRunnable::numMaxIndex=>4)),ENT_QUOTES,AppConfig::character));
			$this->getControl()->equals(htmlspecialchars("<input type=\"text\" name=\"".$name."\" value=\"".$value.
				"\" size=\"4\" maxlength=\"4\" ".$option.">",ENT_QUOTES,AppConfig::character),
				htmlspecialchars(HtmlHelper::text($name, $value, array(ModelRunnable::formIndexOption=>$option,
					ModelRunnable::numMaxIndex=>4,ModelRunnable::formType=>"text")),ENT_QUOTES,AppConfig::character));
		}
		
		public function testTextArea(){
			//cols rows option両方設定無し
			$this->getControl()->equals(htmlspecialchars("<textarea cols=\"".($cols=5)."\" rows=\"".($rows=10)."\" name=\"".($name="test")."\" "
					.">".($value="value")."</textarea>",ENT_QUOTES,AppConfig::character), 
				htmlspecialchars(HtmlHelper::textArea($name, $value, array()),ENT_QUOTES,AppConfig::character));
			//cols設定あり
			$this->getControl()->equals(htmlspecialchars("<textarea cols=\"".($cols=10)."\" rows=\"".($rows=10)."\" name=\"".($name="test")."\" "
					.">".($value="value")."</textarea>",ENT_QUOTES,AppConfig::character),
					htmlspecialchars(HtmlHelper::textArea($name, $value, array(ModelRunnable::formCols=>$cols)),ENT_QUOTES,AppConfig::character));
			//rows設定有
			$this->getControl()->equals(htmlspecialchars("<textarea cols=\"".($cols=5)."\" rows=\"".($rows=20)."\" name=\"".($name="test")."\" "
					.">".($value="value")."</textarea>",ENT_QUOTES,AppConfig::character),
					htmlspecialchars(HtmlHelper::textArea($name, $value, array(ModelRunnable::formRows=>$rows)),ENT_QUOTES,AppConfig::character));
			//option設定有
			$this->getControl()->equals(htmlspecialchars("<textarea cols=\"".($cols=5)."\" rows=\"".($rows=10)."\" name=\"".($name="test")."\" "
					.($option="option").">".($value="value")."</textarea>",ENT_QUOTES,AppConfig::character),
					htmlspecialchars(HtmlHelper::textArea($name, $value, array(ModelRunnable::formIndexOption=>$option)),ENT_QUOTES,AppConfig::character));
			//全部設定有
			$this->getControl()->equals(htmlspecialchars("<textarea cols=\"".($cols=10)."\" rows=\"".($rows=50)."\" name=\"".($name="test_test")."\" "
					.($option="option_test").">".($value="value")."</textarea>",ENT_QUOTES,AppConfig::character),
					htmlspecialchars(HtmlHelper::textArea($name, $value, 
					array(ModelRunnable::formCols=>$cols,ModelRunnable::formRows=>$rows,ModelRunnable::formIndexOption=>$option)),ENT_QUOTES,AppConfig::character));
		}
	}
?>