<?php
	AppConfig::includeModel(array("Sl_user"));

	abstract class ApplicationBase extends Controller{
		/**
	 	* constructor
	 	*/
		public function ApplicationBase(){
			parent::Controller();
		}
		public function getRootList(){
			return array();
		}
		protected function setupAuthSuccess(){
			if(AppConfig::$config->isSsl()){
				AppConfig::$config->redirectHost($this->getDB(self::basicDbIndex));
			}
			$this->setAppMenu($this->getViewPath()."loginMenu.php");
		}
		/**
		 * リダイレクト処理
		 */
		public function redirector(){
			if($this->getUser()===null){//認証失敗
				AppConfig::redirectSsl();
			}
			AppConfig::redirectHost($this->getDB(self::basicDbIndex));
		}
		protected function setupAuthFailure(){
			if(!AppConfig::$config->isSsl()){
				AppConfig::$config->redirectSsl();
			}
		}
		public function printJs(){
			print("<script type='text/javascript' src='slup_php/resource/js/slc.js'></script>");
		}
		public function createDBDriver(){
			global $dbParameter_0;
			$this->setDB(ControllerRunnable::basicDbIndex,new MySQLDriver($dbParameter_0,$this));
		}
	}
?>