<?php
	class Controller_test extends Lf_testCase{
		/**
		 * @var Controller_mock
		 */
		private $app;
		

		
		/**
		*	init,createDBDriver,getDB,setupSessionStart,getViewPath,getAction,getTitle,getAppMenu
		 */
		public function create(){
			$_GET[AppConfigRunnable::appAccessIndex]="test";
			$this->app=new Controller_mock();
			$this->sessionClear();//一応セッションをクリアーする
			//init
				//createDBDriver,getDB
				$this->getControl()->equalsNotNull($this->app->getDB(ControllerRunnable::basicDbIndex));
				//setupSessionStart
				if(AppConfig::isSsl()){
					$this->getControl()->equals($this->app->getJsAppUrl(),AppConfig::getSslHost());
				}else{
					$this->getControl()->equals($this->app->getJsAppUrl(),AppConfig::getHost());
				}
				//setup
					//getViewPath
					$this->getControl()->equals($this->app->getViewPath(),AppConfig::getViewPath());
					//getAction
					$this->getControl()->equals($this->app->getAction(),Controller::action_index);
					//getTitle
					$this->getControl()->equals($this->app->getTitle(),AppConfigRunnable::formalName);
					//getAppMenu
					$this->getControl()->equals($this->app->getAppMenu(), $this->app->getViewPath().AppConfigRunnable::defaultMenuFile);
					//setupSessionData temp 初期アクセスパターン
					$this->setupSessionData_runAfter();	
		}
		/**
		 */
		public function testAccessDistribution(){
			$ua=$_SERVER['HTTP_USER_AGENT'];
			//スマートフォン
			$_SERVER['HTTP_USER_AGENT']="Mozilla/5.0 (iPhone; U; CPU iPhone OS 2_0_1 like Mac OS X; ja-jp) ";
			$this->app->setup_test();
			$this->getControl()->equals($this->app->getViewPath(),AppConfig::getViewPath().AppConfigRunnable::spView);
			$_SERVER['HTTP_USER_AGENT']=$ua;
			$this->app->setup_test();
		}
		//>getTrueFinger and isFingerprint
		public function testGetTrueFinger(){
			$str=AppConfigRunnable::fingerprint;
			if ( ! empty( $_SERVER['HTTP_USER_AGENT'])){
				$str .= $_SERVER['HTTP_USER_AGENT'];
			}
			if ( ! empty( $_SERVER['HTTP_ACCEPT_CHARSET'])){
				$str .= $_SERVER['HTTP_ACCEPT_CHARSET'];
			}
			$str .= session_id();
			$str=md5( $str );
			$this->getControl()->equals($str,$this->app->getTrueFinger_test());
			$this->getControl()->equalsTrue($this->app->isFingerprint_test($str));
			$this->getControl()->equals(false,$this->app->isFingerprint_test($str."a"));
		}
		public function testGetAuthDB() {
			$this->getControl()->equalsObj($this->app->getAuthDB_test(),$this->app->getDB(ControllerRunnable::basicDbIndex));
		}
		
		//セッションを登録,SetupSessionData セッション有
		public function testRegisterSessionUser(){
			//id fraud.
			$this->app->setUser_test(new Sl_user());
			$this->app->getUser()->set(Sl_user::id, "-1");
			$this->app->getUser()->set(Sl_user::password, TestUser::testPasswordValue);
			$this->getControl()->equals(false,$this->app->registerSessionUser_test());
			$this->app->setupSessionData_test();
			//パスワード不正
			$this->app->setUser_test(new Sl_user());
			$this->app->getUser()->set(Sl_user::id, TestUser::id);
			$this->app->getUser()->set(Sl_user::password, "test");
			$this->getControl()->equals(false,$this->app->registerSessionUser_test());
			$this->app->setupSessionData_test();
			//規制値不足
			$this->app->getAuthDB_test()->startTransaction();
			$user=Sl_user::findBy($this->app->getAuthDB_test(),Sl_user::id,TestUser::id);
			$user[0]->set(Sl_user::restriction,AppConfig::restrictionLoginValue);
			Sl_user::save($this->app->getDB(ControllerRunnable::basicDbIndex),$user[0]);
			$this->app->setUser_test(new Sl_user());
			$this->app->getUser()->set(Sl_user::id,TestUser::id);
			$this->app->getUser()->set(Sl_user::password,md5(TestUser::testPasswordValue));
			$this->getControl()->equals($this->app->registerSessionUser_test(),false);
			$this->app->getDB(Controller::basicDbIndex)->rollback();
			$this->sessionClear();	
			//ログイン成功
			$this->getControl()->equalsNull($this->app->getUser());
			$this->login();
			$this->getControl()->equals($this->app->getUser()->get(Sl_user::id),TestUser::id);
			$this->sessionClear();
		}
		
		protected function login(){//ログインさせる
			$this->app->setUser_test(new Sl_user());
			$this->app->getUser()->set(Sl_user::id,TestUser::id);
			$this->app->getUser()->set(Sl_user::password, md5(TestUser::testPasswordValue));
			$this->getControl()->equalsTrue($this->app->registerSessionUser_test());
			$this->app->setUser_test(null);
			$this->app->setupSessionData_test();
		}
		
		public function testSetupSessionData(){
			//ユーザーセッションが有
			$this->login();
			HtmlHelper::setSessionParam(AppConfigRunnable::userSessionIndex,($dummy="___****"));
			$this->app->setupSessionData_test();
			$this->setupSessionData_runAfter();
			//ユーザーセッションなし
			$this->login();
			HtmlHelper::setSessionParam(AppConfigRunnable::fingerPrintIndex,"test");
			$this->app->setupSessionData_test();
			$this->setupSessionData_runAfter();
			//フィンガープリントが不正
			$this->login();
			HtmlHelper::setSessionParam(AppConfigRunnable::fingerPrintIndex,"test");
			$this->app->setupSessionData_test();
			$this->setupSessionData_runAfter();
			//IDが不正
			$this->login();
			HtmlHelper::setSessionParam(AppConfigRunnable::userSessionIndex,$dummy);
			$this->app->setupSessionData_test();
			$this->setupSessionData_runAfter();
			//規制値不正によるログイン失敗
			$this->login();//ログインさせる
			$this->getControl()->equals($this->app->getUser()->get(Sl_user::restriction),"0");
			$this->app->getAuthDB_test()->startTransaction();
			$this->app->getUser()->set(Sl_user::restriction, AppConfigRunnable::restrictionLoginValue);
			Sl_user::save($this->app->getDB(ControllerRunnable::basicDbIndex),$this->app->getUser());
			$this->app->setupSessionData_test();
			$this->setupSessionData_runAfter();
			$this->app->getDB(Controller::basicDbIndex)->rollback();
			
			
			
		}
			protected function setupSessionData_runAfter(){
				$this->getControl()->equalsNull(HtmlHelper::getSessionParam(AppConfigRunnable::userSessionIndex));
				$this->getControl()->equalsNull(HtmlHelper::getSessionParam(AppConfigRunnable::fingerPrintIndex));
				$this->getControl()->equalsNull($this->app->getUser());
				$this->sessionClear();
			}
			
			protected function sessionClear(){
				$_SESSION=array();
				$this->app->setUser_test(null);
			}
			
		public function testGetActionIndex(){
			$this->getControl()->equals($this->app->getActionIndex(),AppConfigRunnable::actionAccessIndex);
		}
		public function testGetActionParam() {
			$_GET[$this->app->getActionIndex()]="test";
			$this->getControl()->equals($this->app->getActionParam_test(),"test");
			unset($_GET[$this->app->getActionIndex()]);
			$this->getControl()->equalsNull($this->app->getActionParam_test());
		}
		public function testAnalysisProcess(){
			$_GET[$this->app->getActionIndex()]="test";
			$this->getControl()->equals($this->app->analysisAction_test(),"test");
			$_GET[$this->app->getActionIndex()]="testtest";
			$this->getControl()->equals($this->app->analysisAction_test(),Controller::action_index);
			unset($_GET[$this->app->getActionIndex()]);
			$this->getControl()->equals($this->app->analysisAction_test(),Controller::action_index);
		}
		
		
		
		public function testGetControllerName() {
			$this->getControl()->equals("test",Controller::getControllerName());
		}
		
		
		public function testRun(){
			$this->getControl()->equals($this->app->run(),
				$this->app->getViewPath().Controller::getControllerName().CommonResources::slash.Controller::action_index.".php");
		}
	
		public function testGetActionForm(){
			$this->app->setUser_test(new Sl_user());
			Sl_user::setupSecurity("5 minute", $this->app->getUser());
			$this->getControl()->equals(htmlspecialchars(
				$this->app->getActionForm("test" ,"login","POST","",$this->app->getUser()),ENT_QUOTES,AppConfig::character),
				htmlspecialchars("<form name=\"test\" action=\"".AppConfig::$appHomeFromBrowserPath.$this->app->getControllerName().CommonResources::slash."login\" method=\"POST\" >".
					HtmlHelper::input("hidden",Sl_user::parseFormName(Model::security),
					$this->app->getUser()->get(ModelRunnable::security),""),ENT_QUOTES,AppConfig::character));
			$this->getControl()->equals(htmlspecialchars(
				$this->app->getActionForm("test" ,null,"GET","id='login'",$this->app->getUser()),ENT_QUOTES,AppConfigRunnable::character),
				htmlspecialchars("<form name=\"test\" action=\"".AppConfig::$appHomeFromBrowserPath.$this->app->getControllerName().CommonResources::slash.
					"\" method=\"GET\" id='login'>".
					HtmlHelper::input("hidden",Sl_user::parseFormName(Model::security),
					$this->app->getUser()->get(ModelRunnable::security),""),ENT_QUOTES,AppConfig::character));
			$_SESSION=array();
			//$this->app->setSecurityKey(null);
		}
		
	
		public function exitTest(){
			unset($_GET[AppConfigRunnable::appAccessIndex]);
			$this->app->exitSession();
		}
		
		public function testGetExetension() {
			$this->getControl()->equals($this->app->getExetension(),CommonResources::nullCharacter);
		}
		public function testGetJsErrorUrl(){
			$this->getControl()->equals($this->app->getJsErrorUrl(),$this->app->getAppUrl_test());
		}
		public function testGetHeader(){//header for web page.
			$this->getControl()->equals($this->app->getHeader(),$this->app->getViewPath()."header.php");
		}
		public function testGetFooter(){//footer for web page.
			$this->getControl()->equals($this->app->getFooter(),$this->app->getViewPath()."footer.php");
		}
		
		
		
		
	}
?>