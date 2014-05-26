<?php
	class TopErrorMessage{
		public static function getCheckLogin($outputId,$outputPassword){
			return $outputId."もしくは".$outputPassword."が間違っている可能性があります。";
		}
		public static function getTempRegister($mail){
			return "ご登録頂きました".$mail."に確認用メールを送信しました！";
		}
		public static function getCheckPasswordConfirmation($outputName){
			return $outputName."と一致しません。";
		}
		public static function getTempRegisterSub(){
			return "【".AppConfigRunnable::formalName."】　登録のご案内";
		}
		public static function getTempRegisterText($url){
			return "このメールは、入力いただいたメールアドレス宛に自動的にお送りしています。
下記のURLにアクセスして登録を続けてください。
		
".$url.
"
（このURLは24時間を超えると使用できません。）";
		}
		public static function getCheckNotRegister($mail){
			return "この".$mail."では登録がありません。";
		}
		public static function getReissueIdSub(){
			return "【".AppConfigRunnable::formalName."】　ID再通知のご案内";
		}
		public static function getReissueIdText($id){
			return "このメールは、入力いただいたメールアドレス宛に自動的にお送りしています。
あなたのIDは、
		
".$id."
		
です。";
		}
		public static function getReissueIdSuccess($mail,$outputId){
			return "ご登録頂いている".$mail."に、".$outputId."を送信しました！";
		}
		public static function getReissuePasswordSub(){
			return "【".AppConfigRunnable::formalName."】　パスワード再設定のご案内";
		}
		public static function getReissuePasswordText($url){
			return "このメールは、入力いただいたメールアドレス宛に自動的にお送りしています。
下記のURLにアクセスして再設定をしてください。
		
".$url."
";
		}
		public static function getReissuePasswordSuccess($mail){
			return "ご登録頂いている".$mail."に、再設定用のURLを送信しました！";
		}
		public static function getReissuePasswordCompleteSuccess($outputId){
			return $outputId."を再設定しました！";
		}
		
		
		
		
		
	}
	
	
?>