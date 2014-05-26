<?php
class HtmlHelper{
	
	/**
	 * index for sex param list.
	 * @var string
	 */
	const form_sexMan="0";
	const form_sexWoman="1";

	/**
	 * form method name.
	 */
	const text="text";
	const textArea="textArea";
	const password="password";
	const checkBox="checkBox";
	
	
	/**
	 * Generate form.
	 * @param string $name
	 * @param string $action
	 * @param string $method
	 * @param string $option
	 * @return string
	 */
	public static function form($name,$action ,$method ,$option ) {
		return "<form name=\"".$name."\" action=\"".$action."\" method=\"".$method."\" ".$option.">";
	}
	
	
	/**
	 * Generate input tag.
	 * インプットタグを生成
	 * @param string $name input name.
	 * @param string $value value.
	 * @param string $option other.
	 * @return string
	 */
	public static function text($name,$value,$option){
		return "<input type=\"".(isset($option[ModelRunnable::formType]) ? ($formType=$option[ModelRunnable::formType]):($formType="text")).
			"\" name=\"".$name."\" value=\"".(($formType===self::password)? CommonResources::nullCharacter:$value)."\" ".
			(isset($option[ModelRunnable::numMaxIndex]) ? "size=\"".$option[ModelRunnable::numMaxIndex].
				"\" maxlength=\"".$option[ModelRunnable::numMaxIndex]."\" ":CommonResources::nullCharacter).
			(isset($option[ModelRunnable::formIndexOption]) ? $option[ModelRunnable::formIndexOption]:CommonResources::nullCharacter).">";
	}	
	
	/**
	 * Generate text tag.
	  * @param string $name input name.
	 * @param string $value value.
	 * @param string $option other.
	 * @return string
	 */
	public static function textArea($name,$value,$option){
		return "<textarea cols=\"".(isset($option[ModelRunnable::formCols]) ? $option[ModelRunnable::formCols]:"5").
			"\" rows=\"".(isset($option[ModelRunnable::formRows]) ? $option[ModelRunnable::formRows]:"10").
			"\" name=\"".$name."\" ".
			(isset($option[ModelRunnable::formIndexOption]) ? $option[ModelRunnable::formIndexOption]:CommonResources::nullCharacter).">".$value."</textarea>";
	}
	/**
	 * Generate checkBox.
	 * @param string $name input name.
	 * @param string $value value.
	 * @param string $option other.
	 * @return string
	 */
	public static function checkBox($name,$value,$option){
		return "<input type='checkbox' name='".$name."' value='".
			$option[ModelRunnable::equalsIndex]."' ".
			($value===$option[ModelRunnable::equalsIndex]?"checked":CommonResources::nullCharacter)." >".
			(isset($option[ModelRunnable::formIndexOption]) ? $option[ModelRunnable::formIndexOption]:CommonResources::nullCharacter);
	}
	
	
	
	/**
	 * Generate radio input tag.
	  * @param string $name input name.
	 * @param string $value value.
	 * @param string $option other.
	 * @return string
	 */
	public static function radio($name,$value,$option,$header,$footer){
		$list=$option[ModelRunnable::formList];
		$tag=$header;
		for($i=0;$i<count($list);$i++){
			$checked=CommonResources::nullCharacter;
			if($value===$list[$i][self::value]){
				$checked="checked";
			}
			$tag.="<INPUT TYPE='radio' name='".$formName."' VALUE='".
				$list[$i][self::value]."'".$list[$i][self::option].">".$list[$i][self::output];
		}
		return $tag.$footer;
	}
	/**
	 * Get param list for radio button.
	 * ラジオボタンのパラメーターリストを取得
	 * @param string $option
	 * @param string $manJpName
	 * @param string $womanJpName
	 */
	public static function getSexRadioParam($option,$manJpName,$womanJpName){
		return array(
			array(self::value=>self::form_sexMan,
				self::output=>$manJpName,
				self::option=>" id='idSex_".self::form_sexMan."'".$option),
			array(self::value=>self::form_sexWoman,
				self::output=>$womanJpName,
				self::option=>" id='idSex_".self::form_sexWoman."'".$option));	
	}
	/**
	 * Generate select tag.
	 * @param string[][] $list
	 * @param string $name
	 * @param string $option
	 * @return string
	 */
	public static function select($name,$value,$option){
		$select="<select name=\"".$name."\" ".$option.">";
		for($i=0;$i<count($list);$i++){
			$selected=CommonResources::nullCharacter;
			if($value===$list[$i][self::formList_index_value]){
				$selected="selected";
			}
			$select.="<option value=\"".$list[$i][self::formList_index_value]."\" ".">".$list[$i][self::output];
		}
		return $select."</select>";
	}
	/**
	 * テスト前
	 */
	/**
	 * Return the select tag to set number to value and text.
	 * 値とテキストが番号で設定されたセレクトタグを返す
	 * @param string $name
	 * @param string $first first num.最初の番号
	 * @param string $end end num.最後の番号
	 * @param int $selectNum. default select number.
	 * @return string
	 */
	public static function numbersSelect($name,$first,$end,$selectNum){
		$select="<select id='".$name."' name='".$name."'>";
		for($i=$first;$i<=$end;$i++){
			if($i==$selectNum){
				$select.="<option value='".$i."' selected>".$i."</option>";
			}else{
				$select.="<option value='".$i."'>".$i."</option>";
			}
		}
		return $select."</select>";
	}
	
	/**
	 * Generate checkBox list.
	 * @param string $name
	 * @param string $value
	 * @param string $list
	 * @param int $br Multiple for new line.改行する倍数
	 * @return string
	 */
	public static function checkBox_list($name,$value,$list,$br){
		$input="";
		for($i=0;$i<count($list);$i++){
			if($i%$br==0&&$i!=0){
				print("<br/>");
			}
			$input=$this->generateCheckBoxInput($name.$list[$i][self::option],
					$list[$i][self::value],
					$value,
					$list[$i][self::output]);
		}
		return $input;
	}
	/**
	 * Generate upload form.
	 * @param string $form_name
	 * @param unknown_type $action
	 * @return string
	 */
	public static function upload_form($form_name,$action){
		return "<form name=\"".$form_name."\" action=\"".$action."\" ENCTYPE=\"MULTIPART/FORM-DATA\" method=\"post\" >";
	}
	/**
	 * Generate upload input.
	 * @param string $input_name
	 * @param string $size
	 */
	public static function upload_input($input_name,$size){
		return "<input name='".$input_name."' type='file' size='".$size."'>";
	}
	/**
	 * escape the parameter.
	 * パラメータをエスケープする
	 * @param String $value
	 * @return String
	 */
	public static function getEscapeParam($value){
		return htmlspecialchars(trim($value),ENT_QUOTES,AppConfigRunnable::character);
	}
	/**
	 * to get the post parameter.
	 * POSTパラメータを取得する
	 * @param String $index
	 * @return string
	 */
	public static function getPostParam($index){
		if(isset($_POST[$index])){
			return self::getEscapeParam($_POST[$index]);
		}
		return NULL;
	}
	/**
	 * get the get parameter.
	 * GETパラメータを取得する
	 * @param String $index
	 * @return String
	 */
	public static function getGetParam($index){
		if(isset($_GET[$index])){
			return self::getEscapeParam($_GET[$index]);
		}
		return NULL;
	}
	/**
	 * get the post parameter for ajax.
	 * ポストパラメータを取得する（Ajax用）
	 * @param String $index
	 * @return String
	 */
	public static function getPostParam_ajax($index){
		if(isset($_POST[$index])){
			$str=mb_convert_encoding($_POST[$index],AppConfigRunnable::character,"UTF-8");
			return self::getEscapeParam($str);
		}
		return NULL;
	}
	/**
	 * get the get parameter for ajax.
	 * ゲットパラメータを取得する（Ajax用）
	 * @param String $index
	 * @return String
	 */
	public static function getGetParam_ajax($index) {
		if(isset($_GET[$index])){
			$str=mb_convert_encoding($_GET[$index],AppConfigRunnable::character,"UTF-8");
			return self::getEscapeParam($str);
		}
		return NULL;
	}
	/**
	 * get the session parameter.
	 * セッション変数を取得する
	 * @param String $name
	 * @return mixed
	 */
	public static function getSessionParam($name){
		if(!isset($_SESSION[$name])){
			return NULL;//If are not stored value it return NULL.
		}
		return $_SESSION[$name];
	}
	/**
	 *set session parameter.
	 * @param String $name
	 * @param mixed $value
	 */
	public static function setSessionParam($name,$value){
		$_SESSION[$name]=$value;
	}
	/**
	 * Get object from session.
	 * セッションからオブジェクトを取得
	 * @param string $name
	 * @return Object
	 */
	public static function getSessionObj($name) {
		if(isset($_SESSION[$name])){
			return unserialize($_SESSION[$name]);
		}
		return NULL;
	}
	/**
	 * Set object to session.
	 * オブジェクトをセッションに格納
	 * @param string $name
	 * @param Object $obj
	 */
	public static function setSessionObj($name,$obj) {
		$_SESSION[$name] = serialize($obj);
	}
	/**
	 * escape the session id.
	 * セッションIDをエスケープする
	 * @return String
	 */
	public static function getEscapeSessionId(){
		return self::getEscapeParam(session_id());
	}
	/**
	 * @see ControlllerRunnable
	 */
	public static function isCheck_mail($mail_address){
		if(preg_match('/^[a-zA-Z0-9_\.\-]+?@[A-Za-z0-9_\.\-]+$/',$mail_address)===0){
			return false;
		}
		return true;
	}
	/**
	 * Check whether it's right as alnum.
	 * 半角英字か
	 * @param string $text
	 * @return boolean
	 */
	public static function isAlnum($text) {
		if(preg_match("/^[a-zA-Z]+$/",$text)===0){
			return false;
		}
		return true;
	}
	/**
	 * Check whether it's right as url.
	 * URLとして正しいか
	 * @param string $value
	 * @return boolean
	 */
	public static function isUrl($value){
		if(preg_match('/^(https?|ftp)(:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)$/',$value)===0){
			return false;
		}
		return true;
	}
	/**
	 * Check whether it's right as decbin.
	 * ２進数か
	 * @param string $value
	 * @return boolean
	 */
	public static function isDecbin($value){
		if(preg_match("/^[0-1]+$/",$value)===0){
			return false;
		}
		return true;
	}
	/**
	 * Get the URL for the transition destination.
	 * 遷移先のURLを取得
	 * @param string $actionName
	 * @param string $callMethod
	 */
	public static function getActionUrl($actionName,$callMethod){
		$url=AppConfig::$appHomeFromBrowserPath.$actionName;
		if($callMethod!==null){
			$url.=CommonResources::slash.$callMethod;
		}
		return $url;
	}
	/**
	 * Check access from smartphone.
	 * スマートフォンからのアクセスかチェック
	 * @return string
	 */
	public static function isSpAccess(){//Check access from smartphone.スマートフォンからのアクセスかチェック
		return preg_match('#\b(iP(hone|od);|Android )|dream|blackberry9500|blackberry9530|blackberry9520|blackberry9550|blackberry9800|CUPCAKE|webOS|incognito|webmate#',$_SERVER['HTTP_USER_AGENT']);
	}
	/**
	 * Check ajax access..
	 * ajaxでのアクセスか調べる
	 * @return boolean true=>ajax
	 */
	public static function isAjax(){
		return (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') ;
	}
	/**
	 * Get ip address.
	 * IPアドレスを取得
	 * @return string
	 */
	public static function getAccessIp(){
		return getenv("REMOTE_ADDR");
	}
	public static function br2nl($string){
		return preg_replace('/<br[[:space:]]*\/?[[:space:]]*>/i', CommonResources::nullCharacter, $string);
	}
	/**
	 * send mail.
	 * メール送信
	 * @param string $address
	 * @param string $subject
	 * @param string $value
	 * @param string $mail_header
	 * @return boolean
	 */
	public static function sendMail($address,$subject,$value,$mail_header){
		$header="From: ".$mail_header;
		mb_language('Japanese');
		mb_internal_encoding(AppConfigRunnable::character);
		return mb_send_mail($address, $subject,$value,$header);
	}
	
	
}

?>