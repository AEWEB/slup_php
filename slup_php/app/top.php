<?php
	AppConfig::includeModel(array("Sl_user"));
	class TopController extends ApplicationBase{
		
		/**
		 * temp registered key index.
		 */
		const tempKey="tempKey";
		/**
		 * Session Index for reissue password.
		 */
		const reissuePasswordId="rpId";
		
		public function index(){
			if($this->getUser()===null){//認証失敗
				$this->setupAuthFailure();
				$this->setUser(Sl_user::formCheck());
				if(Sl_user::isValidation()){
					if(!$this->registerSessionUser()){
						Sl_user::putErrorMessage(TopErrorMessage::getCheckLogin(ModelResource::sl_user_id,ModelResource::sl_user_password));
					}else{
						AppConfig::$config->redirectHost($this->getDB(self::basicDbIndex));
					}
				}
				return "login";
			}
			$this->setupAuthSuccess();
		}
		
		public function logout(){
			$this->exitSession();
			AppConfig::$config->redirectSsl();
		}
		
		
		
		public function register() {
			if($this->getUser()===null){//認証失敗
				$this->setupAuthFailure();
				Sl_user::setupRegisterColumn();
				$this->setUser(Sl_user::formCheck());
				if(Sl_user::isValidation()){
					$this->getAuthDB()->startTransaction();
					Sl_user::deleteExpiredData($this->getAuthDB());//仮登録で24時間以上立っているものをすべて削除	
					if(count($user=Sl_user::find($this->getAuthDB(),Sl_user::createModel(array(Sl_user::mid=>$this->getUser()->get(Sl_user::mid)))))>0&&//メールアドレスは存在
						$user[0]->get(Sl_user::restriction)!==Sl_user::tempRestriction){//仮登録状態ではない
						Sl_user::putErrorMessage(ErrorMessage::getCheckDuplication(ModelResource::sl_user_m_id));	
					}else{
						if(count($user)===0){//登録されていない							
							$this->getUser()->set(Sl_user::id,substr(md5(strtotime("now").$_SERVER['HTTP_USER_AGENT'].$this->getUser()->get(Sl_user::mid)),0,20));
							$this->getUser()->set(Sl_user::imageurl, AppConfig::getImagePath().AppConfig::defaultImage);
							$this->getUser()->set(Sl_user::date, date("Y/m/d H:i:s",strtotime("+1 day")));
							$this->getUser()->set(Sl_user::name, Sl_user::tempUserParam);
							$this->getUser()->set(Sl_user::password, Sl_user::tempUserParam);
							$this->getUser()->set(Sl_user::restriction, Sl_user::tempRestriction);
							$this->getUser()->set(Sl_user::device,AppConfigRunnable::usualCareer_id);
							Sl_user::insert($this->getAuthDB(), $this->getUser()) ? true:$this->redirector();
						}else{
							$this->getUser()->set(Sl_user::id,$user[0]->get(Sl_user::id));
						}
						HtmlHelper::sendMail($this->getUser()->get(Sl_user::mid), 
							TopErrorMessage::getTempRegisterSub(), 
							TopErrorMessage::getTempRegisterText(AppConfig::getSslHost().AppConfigRunnable::redirectUrl.CommonResources::slash."registerComplete".CommonResources::question.self::tempKey."=".$this->getUser()->get(Sl_user::id)).$this->getMailFooter(),
							AppConfigRunnable::systemMailAdd);						
						$this->getAuthDB()->commit();
						Sl_user::putErrorMessage(TopErrorMessage::getTempRegister(ModelResource::sl_user_m_id));
						return "response";
					}
					$this->getAuthDB()->commit();
				}
				return "login";
			}
			$this->redirector();
		}		
		
		public function registerComplete(){
			if($this->getUser()===null){//認証失敗
				$this->setupAuthFailure();
				if((($key=HtmlHelper::getGetParam(self::tempKey))===null
					&&($key=HtmlHelper::getSessionParam(self::tempKey))===null)){
					AppConfig::$config->redirectSsl();
				}
				HtmlHelper::setSessionParam(self::tempKey,$key);
				$this->getAuthDB()->startTransaction();
				Sl_user::deleteExpiredData($this->getAuthDB());
				if(count($tempUser=Sl_user::find($this->getAuthDB(),Sl_user::createModel(array(sl_user::id=>$key,Sl_user::restriction=>Sl_user::tempRestriction))))>0){//仮登録状態ではない
					Sl_user::setupRegisterCompleteColumn();
					$this->setUser(Sl_user::formCheck());
					if(Sl_user::isValidation()){
						if($this->parsePassword($this->getUser()->get(Sl_user::password))
							!==$this->parsePassword($this->getUser()->get(Sl_user::passwordConfirmation))){
							Sl_user::putErrorMessage(TopErrorMessage::getCheckPasswordConfirmation(ModelResource::sl_user_passwordConfirmation));
						}else if(count(Sl_user::find($this->getAuthDB(),Sl_user::createModel(array(Sl_user::id=>$this->getUser()->get(Sl_user::id)))))>0){
							Sl_user::putErrorMessage(ErrorMessage::getCheckDuplication(ModelResource::sl_user_id));
						}else{
							$tempUser[0]->set(Sl_user::name, $this->getUser()->get(Sl_user::id));
							$tempUser[0]->set(Sl_user::password, $this->parsePassword($this->getUser()->get(Sl_user::password)));
							$tempUser[0]->set(Sl_user::restriction, AppConfigRunnable::usualRestriction);
							Sl_user::save($this->getAuthDB(),$tempUser[0],$this->getUser()->get(Sl_user::id)) ? true:null;
							//ログイン状態にする
							HtmlHelper::setSessionParam(AppConfigRunnable::userSessionIndex,$this->getUser()->get(Sl_user::id));
							HtmlHelper::setSessionParam(AppConfigRunnable::fingerPrintIndex,$this->getTrueFinger());
							$this->getAuthDB()->commit();
							AppConfig::$config->redirectHost($this->getDB(self::basicDbIndex));
						}
					}
					$this->getUser()->set(Sl_user::mid, $tempUser[0]->get(Sl_user::mid));
					$this->getAuthDB()->commit();
					return "registerComplete";
				}
				$this->getAuthDB()->commit();
			}
			$this->redirector();
		}
		
		public function terms(){
			if($this->getUser()===null){//認証失敗
				$this->setupAuthFailure();
				return "terms";
			}
			$this->redirector();
		}
		/**
		 * ヘルプページの表示
		 */
		public function showHelp() {
			if($this->getUser()===null){//認証失敗
				$this->setupAuthFailure();
			}else{
				$this->setupAuthSuccess();
			}
			return "help";
		}
		/**
		 * ID再発行
		 */
		public function reissueId() {
			if($this->getUser()===null){//認証失敗
				$this->setupAuthFailure();
				Sl_user::setupRegisterColumn();
				$this->setUser(Sl_user::formCheck());
				if(Sl_user::isValidation()){
					if(count($user=Sl_user::find($this->getAuthDB(),Sl_user::createModel(array(Sl_user::mid=>$this->getUser()->get(Sl_user::mid)))))<1||//メールアドレスは存在
						$user[0]->get(Sl_user::restriction)>=AppConfigRunnable::restrictionLoginValue){//規制値以上の値である
						Sl_user::putErrorMessage(TopErrorMessage::getCheckNotRegister(ModelResource::sl_user_m_id));
					}else{
						HtmlHelper::sendMail($user[0]->get(Sl_user::mid), 
							TopErrorMessage::getReissueIdSub(), 
							TopErrorMessage::getReissueIdText($user[0]->get(Sl_user::id)).$this->getMailFooter(),
							AppConfigRunnable::systemMailAdd);	
						Sl_user::putErrorMessage(TopErrorMessage::getReissueIdSuccess(ModelResource::sl_user_password, ModelResource::sl_user_id));
						return "response";
					}
					$this->getAuthDB()->commit();
				}
				return "reissueId";
			}
			$this->redirector();
		}
		public function reissuePassword(){
			if($this->getUser()===null){//認証失敗
				$this->setupAuthFailure();
				Sl_user::setupRegisterColumn();
				$this->setUser(Sl_user::formCheck());
				if(Sl_user::isValidation()){
					if(count($user=Sl_user::find($this->getAuthDB(),Sl_user::createModel(array(Sl_user::mid=>$this->getUser()->get(Sl_user::mid)))))<1||//メールアドレスは存在
						$user[0]->get(Sl_user::restriction)>=AppConfigRunnable::restrictionLoginValue){//規制値以上の値である
						Sl_user::putErrorMessage(TopErrorMessage::getCheckNotRegister(ModelResource::sl_user_m_id));
					}else{
						$tempKey=substr(md5(strtotime("now").$_SERVER['HTTP_USER_AGENT'].$user[0]->get(Sl_user::id)),0,20);
						HtmlHelper::setSessionParam(self::reissuePasswordId,$user[0]->get(Sl_user::id));
						HtmlHelper::setSessionParam(self::tempKey,$tempKey);
						$text=AppConfig::getSslHost().AppConfigRunnable::redirectUrl.CommonResources::slash."reissuePasswordComplete".CommonResources::question.self::tempKey."=".$tempKey;
						HtmlHelper::sendMail($user[0]->get(Sl_user::mid),
							TopErrorMessage::getReissuePasswordSub(),
							TopErrorMessage::getReissuePasswordText($text).$this->getMailFooter(),
							AppConfigRunnable::systemMailAdd);
						Sl_user::putErrorMessage(TopErrorMessage::getReissuePasswordSuccess(ModelResource::sl_user_m_id));
						return "response";
					}
					$this->getAuthDB()->commit();
				}
				return "reissuePassword";
			}
			$this->redirector();
		}
		public function reissuePasswordComplete(){
			if($this->getUser()===null){//認証失敗
				$this->setupAuthFailure();
				if((($key=HtmlHelper::getGetParam(self::tempKey))===null
					&&($key=HtmlHelper::getSessionParam(self::tempKey."_sub"))===null)){
				}else if(($id=HtmlHelper::getSessionParam(self::reissuePasswordId))===null||
					$key!==HtmlHelper::getSessionParam(self::tempKey)){//キーが不正
					$_SESSION=array();
				}else{
					HtmlHelper::setSessionParam(self::tempKey."_sub",$key);
					$this->log("reissuePasswordComplete key:".$key."/ id:".$id);
					$this->getAuthDB()->startTransaction();
					if(count($user=Sl_user::find($this->getAuthDB(),Sl_user::createModel(array(Sl_user::id=>$id))))<1||//メールアドレスは存在
						$user[0]->get(Sl_user::restriction)>=AppConfigRunnable::restrictionLoginValue){//規制値以上の値である
					}else{
						Sl_user::setupReissuePasswordColumn();
						$this->setUser(Sl_user::formCheck());
						if(Sl_user::isValidation()){
							if($this->parsePassword($this->getUser()->get(Sl_user::password))
								!==$this->parsePassword($this->getUser()->get(Sl_user::passwordConfirmation))){
								Sl_user::putErrorMessage(TopErrorMessage::getCheckPasswordConfirmation(ModelResource::sl_user_passwordConfirmation));
							}else{
								$user[0]->set(Sl_user::password,$this->parsePassword($this->getUser()->get(Sl_user::password)));
								Sl_user::save($this->getAuthDB(),$user[0]);
								$this->getAuthDB()->commit();
								Sl_user::putErrorMessage(TopErrorMessage::getReissuePasswordCompleteSuccess(ModelResource::sl_user_password));
								$_SESSION=array();
								return "response";
							}
						}
						$this->getAuthDB()->rollback();
						return "reissuePasswordComplete";
					}				
					$this->getAuthDB()->rollback();
				}
			}
			$this->redirector();
		}
		/**
		 * action for twitter auth.
		 */
		public function twitterAuth() {
			if($this->getUser()===null){//認証失敗
				AppConfig::includeModel(array("Twitter_user"));
				restore_error_handler();//Clear error hander
				$this->runAuthLogin(Twitter_user::runAuth());
				set_error_handler("errorHandler");
			}
			$this->redirector();
		}
		/**
		 * action for facebook auth.
		 */
		public function facebookAuth(){
			if($this->getUser()===null){//認証失敗
				AppConfig::includeModel(array("Facebook_user"));
				$this->runAuthLogin(Facebook_user::runAuth());
			}
			$this->redirector();
		}
		
		/**
		 * @param AuthModel $user
		 * @return boolean
		 */
		protected function runAuthLogin($user){
			if($user!==null){
				$primary_key=$user->getIdIndex().$user->get(Sl_user::id);
				$this->setUser($user);
				$this->getUser()->set(Sl_user::id,$primary_key);
				$this->getUser()->set(Sl_user::password,($authPass= AppConfig::parseAuthUserPassword($primary_key)));
				$this->getAuthDB()->startTransaction();
				if($this->registerSessionUser()){//ログイン成功
					$this->log("Auth user login success :".print_r($this->getUser(),true));
					$this->getAuthDB()->rollback();
				}else{
					$this->getUser()->set(Sl_user::mid, $this->getUser()->get(Sl_user::id));
					$this->getUser()->set(Sl_user::device, AppConfigRunnable::twitter_careerId);
					$this->getUser()->set(Sl_user::restriction, AppConfigRunnable::usualRestriction);
					$this->getUser()->set(Sl_user::date, date("Y/m/d H:i:s",strtotime("now")));
					$this->getUser()->set(Sl_user::password, $this->parsePassword($this->getUser()->get(Sl_user::password)));
					Sl_user::insert($this->getAuthDB(),$this->getUser());
					$this->getAuthDB()->commit();
					$this->log("Auth user insert success :".print_r($this->getUser(),true));
					$this->getUser()->set(Sl_user::password,$authPass);
					$this->registerSessionUser();
				}
				HtmlHelper::setSessionObj($user->getSaveIndex(), $user);
				AppConfig::redirectHost($this->getAuthDB());
				return true;
			}
			return false;
		}
		
		
		public function getRootList(){
			return array("logout"=>"logout","register"=>"register","registerComplete"=>"registerComplete","terms"=>"terms",
				"showHelp"=>"showHelp","reissueId"=>"reissueId","reissuePassword"=>"reissuePassword","reissuePasswordComplete"=>"reissuePasswordComplete",
				"twitterAuth"=>"twitterAuth","facebookAuth"=>"facebookAuth");
		}
		public function createDBDriver(){
			global $dbParameter_0;
			$this->setDB(ControllerRunnable::basicDbIndex,new MySQLDriver($dbParameter_0,$this));
		}
		public function printCss(){
			print("<link rel='stylesheet' href='".AppConfig::getResourcePathFromBrowser()."css/login.css' type='text/css' />");
		}
	}
?>