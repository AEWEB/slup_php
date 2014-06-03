<?php
	require_once appHome."/config/config.php";
	class AppConfigTest extends AppConfig{
		const testClassIndex="_test";
	
		public static function getTestCasePath(){
			return "testCase/";
		}
		public static function getMockPath(){
			return "mock/";
		}
		public static function redirectHost($db){
			self::clearHttps();
		}
		public static function setupHttps(){
			$_SERVER['HTTPS']="on";
		}
		public static function clearHttps(){
			$_SERVER['HTTPS']="off";
		}
	}
	AppConfig::$config=new AppConfigTest();
	require_once AppConfigTest::getLibPath()."Lftest.php";
	require_once AppConfigTest::getLibPath()."slup.php";
	require_once AppConfigTest::getMockPath()."slup_mock.php";
	require_once AppConfigTest::getConfigPath()."database.php";
	require_once AppConfigTest::getResourcePath()."resource.php";
	AppConfigTest::includeModel(array("Sl_user"));
	require_once AppConfigTest::getAppPath()."applicationBase.php";
	//require_once AppConfigTest::getAppPath()."top.php";
	interface TestUser{
		const id="sample";
		const testPasswordValue="test";
		const testMailValue="test@sl.jp";
		const testMailValue2="expsei2@inter7.jp";
	}
	class My_sample_datas extends Model{
		
		const idValue="1";
		const name="name";
		const nameValue="name";
		const nameOutput="名前";
		const mail="mail";
		const mailValue="mail";
		const mailOutput="メール";
		const tel="tel";
		const telValue="tel";
		const telOutput="電話";
		
		/**
		 * @var SlUser
		 */
		private static $list=array(self::id=>array(self::valueIndex=>"id",self::updateIndex=>false,self::requireIndex=>true),
			self::name=>array(self::valueIndex=>"name",self::numMinIndex=>3,self::numMaxIndex=>10,self::outputIndex=>self::nameOutput),
			self::mail=>array(self::valueIndex=>"mail",self::typeIndex=>self::validation_mailAdd,self::numMinIndex=>5,self::numMaxIndex=>80,
				self::outputIndex=>self::mailOutput),
			self::tel=>array(self::valueIndex=>"tel",self::numMinIndex=>3,self::numMaxIndex=>15,self::typeIndex=>self::validation_ctypeAlnum,
				self::outputIndex=>self::telOutput));
	
		private static $column=null;
	
		public static function getColumnArray(){
			return self::$list;
		}
		public static function getColumn(){
			return self::$column;
		}
		public static function setColumnArray($list){
			self::$list=$list;
		}
		
		/**
		 * @param ModelRunnable $model
		 */
		public static function setColumn($model){
			self::$column=$model;
		}
		
		/**
		 * mock method.
		 */
		public static function runValidation_test($name, $param, $value){
			static::runValidation($name, $param, $value);
		}
		public static function setParam_test($param, $name, $model){
			static::setParam($param, $name, $model);
		}
		public static function getSecurityKeyName_test(){
			return static::getSecurityKeyName();
		}
		
		public static function generateForm_test($name,$param,$model){
			return static::generateForm($name, $param, $model);
		}
		public static function setSessionParamFlag($flag){
			static::$sessionParamFlag=$flag;
		}
		
		
	}
	
	class TestDBParameter extends DatabaseParameter{
		public function TestDBParameter(){
			parent::DatabaseParameter("localhost","root","sohara","test","utf8");
		}
	}

?>