<?php
	class HtmlHelper_test extends Lf_testCase{
		public static function testForm($name,$action ,$method ,$option ) {
			return "<form name=\"".$name."\" action=\"".$action."\" method=\"".$method."\" ".$option.">";
		}
	}
?>