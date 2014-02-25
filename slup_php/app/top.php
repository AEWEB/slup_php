<?php
	class TopController extends ApplicationBase{
		
		public function index(){
			if($this->getUser()===null){//認証失敗
				if(!AppConfig::$config->isSsl()){
					AppConfig::$config->redirectSsl();
				}
				$this->setUser(Sl_user::formCheck());
				if(Sl_user::isValidation()){
					if(!$this->registerSessionUser()){
						Sl_user::putErrorMessage(ModelResource::sl_user_id."もしくは".ModelResource::sl_user_password."が間違っている可能性があります。");
					}else{
						AppConfig::$config->redirectHost();
					}
				}
				$this->setupLoginDisplay();
				return "login";
			}
			if(AppConfigLib::isSsl()){	//認証成功
				$this->redirectHost();
			}
			$this->setupAuthSuccess();
			return "home";
		}
		/**
		 * ログイン用の画面をセットアップ
		 */
		protected function setupLoginDisplay(){
			$this->getUser()->setId($this->generateTextInput("text",UserDB::varId,"20",UserDB::maxLenId,$this->getUser()->getId(),""));
			$this->getUser()->setPassword($this->generateTextInput("password",UserDB::varPassword,"20",UserDB::maxLenPassword,"",""));
			$this->getUser()->setM_id($this->generateTextInput("text",UserDB::varM_id,"50",UserDB::maxLenM_id,"",""));
			$this->setupSecurity("5 minute");
		}
		
		public function getRootList(){
		#	return array("redirector"=>"redirector");
		return array();
		}
		public function createDBDriver(){
		}
	}
?>