<?php
include_once AppConfigTest::getAppPath()."top.php";
	class TopController_mock extends TopController{
		public function authInit(){
			$this->setupSessionData();
			Sl_user::resetError();
			Sl_user::resetErrorItem();
			My_sample_datas::setSessionParamFlag(false);
			$list=Sl_user::getColumnArray();
			$list[Sl_user::mid][ModelRunnable::requireIndex]=false;
			$list[Sl_user::id][ModelRunnable::requireIndex]=true;
			$list[Sl_user::password][ModelRunnable::requireIndex]=true;
			Sl_user::setColumnArray($list);
		}
		public static function getControllerName() {
			return "top";
		}


	}
	class TopController_test extends Lf_testCase{
		/**
		 * @var TopController
		 */
		private $app;
		
		public function create(){
			$this->app=new TopController_mock();
			$this->sessionClear();//一応セッションをクリアーする
		}
		protected function sessionClear(){
			$_SESSION=array();
		}
		
		public function testIndex(){
			AppConfigTest::setupHttps();
			//初期画面
			$this->getControl()->equals($this->app->index(),"login");
			$this->getControl()->equals(Sl_user::getErrorMessage(),CommonResources::nullCharacter);
			$this->app->authInit();
			//セキュリティーエラー
			$_POST[Sl_user::parseFormName(Sl_user::id)]="aaaaaaa";
			$_POST[Sl_user::parseFormName(Sl_user::password)]="aaaaaaaaaa";
			$_POST[Sl_user::parseFormName(Model::security)]="test";
			$this->getControl()->equals($this->app->index(),"login");
			$this->getControl()->equals(Sl_user::getErrorMessage(),CommonResources::nullCharacter);
			$this->getControl()->equals($this->app->getUser()->get(ModelRunnable::security), "test");
			$this->app->authInit();
			//セキュリティーキーをセット
			Sl_user::setupSecurity("5 minute", ($model=new Sl_user()));
			$_POST=array();
			$_POST[Sl_user::parseFormName(Model::security)]=$model->get(ModelRunnable::security);
			$this->getControl()->equals($this->app->index(),"login");
			$list=array(ModelResource::sl_user_id.CommonResources::requireErrorMessage,ModelResource::sl_user_password.CommonResources::requireErrorMessage);
			$this->getControl()->equals(Model::getErrorMessage(),implode($list,"<br/>"));
			$this->app->authInit();
			//リミットチェック---文字数不足
			Sl_user::setupSecurity("5 minute", ($model=new Sl_user()));
			$_POST[Sl_user::parseFormName(Sl_user::id)]="aa";
			$_POST[Sl_user::parseFormName(Sl_user::password)]="aaa";
			$list=array(ModelResource::sl_user_id.ErrorMessage::getCheckMinLength(Sl_user::minLenId),
				ModelResource::sl_user_password.ErrorMessage::getCheckMinLength(Sl_user::minLenPassword));
			$this->getControl()->equals($this->app->index(),"login");
			$this->getControl()->equals(Sl_user::getErrorMessage(),implode($list,"<br/>"));
			$this->app->authInit();
			//リミットチェック--文字数オーバー
			Sl_user::setupSecurity("5 minute", ($model=new Sl_user()));
			$_POST[Sl_user::parseFormName(Sl_user::id)]="aaaaaaaaaaaaaaaaaaaaa";
			$_POST[Sl_user::parseFormName(Sl_user::password)]="aaaaaaaaaaaaaaaaaaaaa";
			$list=array(ModelResource::sl_user_id.ErrorMessage::getCheckMaxLength(Sl_user::maxLenId),
				ModelResource::sl_user_password.ErrorMessage::getCheckMaxLength(Sl_user::maxLenPassword));
			$this->getControl()->equals($this->app->index(),"login");
			$this->getControl()->equals(Sl_user::getErrorMessage(),implode($list,"<br/>"));
			$this->app->authInit();
			//フォーマットチェック
			Sl_user::setupSecurity("5 minute", ($model=new Sl_user()));
			$_POST[Sl_user::parseFormName(Sl_user::id)]="あああ";
			$_POST[Sl_user::parseFormName(Sl_user::password)]="ああああああ";
			$list=array(ModelResource::sl_user_id.CommonResources::validationErrorCtypeAlnum_bar,
				ModelResource::sl_user_password.CommonResources::validationErrorCtypeAlnum);
			$this->getControl()->equals($this->app->index(),"login");
			$this->getControl()->equals(Sl_user::getErrorMessage(),implode($list,"<br/>"));
			$this->app->authInit();	
			//照合チェック
			Sl_user::setupSecurity("5 minute", ($model=new Sl_user()));
			$_POST[Sl_user::parseFormName(Sl_user::id)]="aaaaaaa";
			$_POST[Sl_user::parseFormName(Sl_user::password)]="aaaaaaaaaa";
			$this->getControl()->equals($this->app->index(),"login");
			$this->getControl()->equals(Sl_user::getErrorMessage(),ModelResource::sl_user_id."もしくは".ModelResource::sl_user_password."が間違っている可能性があります。");
			$this->app->authInit();
			//ログイン
			Sl_user::setupSecurity("5 minute", ($model=new Sl_user()));
			$_POST[Sl_user::parseFormName(Sl_user::id)]=TestUser::id;
			$_POST[Sl_user::parseFormName(Sl_user::password)]=TestUser::testPasswordValue;
			$this->getControl()->equals($this->app->index(),"login");
			$this->getControl()->equals(Sl_user::getErrorMessage(),CommonResources::nullCharacter);
			$this->app->authInit();
			//認証成功
			$this->getControl()->equalsNull($this->app->index(),null);
			$_SESSION=array();
			$this->app->authInit();
			$_POST=array();
		}
		
		public function testRegister(){
			AppConfigTest::setupHttps();
			//初期画面
			$this->getControl()->equals($this->app->register(),"login");
			$this->getControl()->equals(Sl_user::getErrorMessage(),"");
			$this->app->authInit();
			//セキュリティーキーをノーセット
			$_POST[Sl_user::parseFormName(Model::security)]="test";
			$this->getControl()->equals($this->app->register(),"login");
			$this->getControl()->equals(Model::getErrorMessage(),CommonResources::nullCharacter);
			$this->app->authInit();
			//セキュリティーキーをセット
			Sl_user::setupSecurity("5 minute", ($model=new Sl_user()));
			$_POST[Sl_user::parseFormName(Model::security)]=$model->get(ModelRunnable::security);
			$this->getControl()->equals($this->app->register(),"login");
			$list=array(ModelResource::sl_user_m_id.CommonResources::requireErrorMessage);
			$this->getControl()->equals(Model::getErrorMessage(),implode($list,"<br/>"));
			$this->app->authInit();
			//リミットチェック---文字数不足
			Sl_user::setupSecurity("5 minute", ($model=new Sl_user()));
			$_POST[Sl_user::parseFormName(Sl_user::mid)]="a@aa";
			$list=array(ModelResource::sl_user_m_id.ErrorMessage::getCheckMinLength(Sl_user::minLenM_id));
			$this->getControl()->equals($this->app->register(),"login");
			$this->getControl()->equals(Model::getErrorMessage(),implode($list,"<br/>"));
			$this->app->authInit();
			//リミットチェック---文字数オーバー
			Sl_user::setupSecurity("5 minute", ($model=new Sl_user()));
			$_POST[Sl_user::parseFormName(Sl_user::mid)]="aaaaaaaaaaaaaaaaa@aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa";
			$list=array(ModelResource::sl_user_m_id.ErrorMessage::getCheckMaxLength(Sl_user::maxLenM_id));
			$this->getControl()->equals($this->app->register(),"login");
			$this->getControl()->equals(Model::getErrorMessage(),implode($list,"<br/>"));
			$this->app->authInit();
			//フォーマットチェック
			Sl_user::setupSecurity("5 minute", ($model=new Sl_user()));
			$_POST[Sl_user::parseFormName(Sl_user::mid)]="artarart";
			$list=array(ModelResource::sl_user_m_id.CommonResources::validationErrorMailAdd);
			$this->getControl()->equals($this->app->register(),"login");
			$this->getControl()->equals(Model::getErrorMessage(),implode($list,"<br/>"));
			$this->app->authInit();
			//照合チェック
			Sl_user::setupSecurity("5 minute", ($model=new Sl_user()));
			$_POST[Sl_user::parseFormName(Sl_user::mid)]=TestUser::testMailValue;
			$this->getControl()->equals($this->app->register(),"login");
			$this->getControl()->equals(Sl_user::getErrorMessage(),ErrorMessage::getCheckDuplication(ModelResource::sl_user_m_id));
			$this->app->authInit();
			//メアド送信
			Sl_user::setupSecurity("5 minute", ($model=new Sl_user()));
			$_POST[Sl_user::parseFormName(Sl_user::mid)]=TestUser::testMailValue2;
			$this->getControl()->equals($this->app->register(),"response");
			$tempId=$this->app->getUser()->get(Sl_user::id);
			$this->app->authInit();
			$_POST=array();
			/**
			 * 本登録画面
			 */
			//初期画面
			$_GET[TopController::tempKey]=$tempId;
			$this->getControl()->equals($this->app->registerComplete(),"registerComplete");
			$this->getControl()->equals(Model::getErrorMessage(),CommonResources::nullCharacter);
			$this->app->authInit();
			$_GET=array();
			//セキュリティーキーをセット
			Sl_user::setupSecurity("5 minute", ($model=new Sl_user()));
			$_POST[Sl_user::parseFormName(Model::security)]=$model->get(ModelRunnable::security);
			$this->getControl()->equals($this->app->registerComplete(),"registerComplete");
			$list=array(ModelResource::sl_user_id.CommonResources::requireErrorMessage,
				ModelResource::sl_user_password.CommonResources::requireErrorMessage,
				ModelResource::sl_user_consentCheck.CommonResources::requireErrorMessage,
				ModelResource::sl_user_passwordConfirmation.CommonResources::requireErrorMessage);
			$this->getControl()->equals(Model::getErrorMessage(),implode($list,"<br/>"));
			$this->app->authInit();
			//リミットチェック--文字数オーバー
			Sl_user::setupSecurity("5 minute", ($model=new Sl_user()));
			$_POST[Sl_user::parseFormName(Sl_user::id)]="aaaaaaaaaaaaaaaaaaaaa";
			$_POST[Sl_user::parseFormName(Sl_user::password)]="aaaaaaaaaaaaaaaaaaaaa";
			$list=array(ModelResource::sl_user_id.ErrorMessage::getCheckMaxLength(Sl_user::maxLenId),
				ModelResource::sl_user_password.ErrorMessage::getCheckMaxLength(Sl_user::maxLenPassword),
				ModelResource::sl_user_consentCheck.CommonResources::requireErrorMessage,
				ModelResource::sl_user_passwordConfirmation.CommonResources::requireErrorMessage);
			$this->getControl()->equals($this->app->registerComplete(),"registerComplete");
			$this->getControl()->equals(Model::getErrorMessage(),implode($list,"<br/>"));
			$this->app->authInit();
			//リミットチェック---文字数不足
			Sl_user::setupSecurity("5 minute", ($model=new Sl_user()));
			$_POST[Sl_user::parseFormName(Sl_user::id)]="aa";
			$_POST[Sl_user::parseFormName(Sl_user::password)]="aaa";
			$list=array(ModelResource::sl_user_id.ErrorMessage::getCheckMinLength(Sl_user::minLenId),
					ModelResource::sl_user_password.ErrorMessage::getCheckMinLength(Sl_user::minLenPassword),
					ModelResource::sl_user_consentCheck.CommonResources::requireErrorMessage,
					ModelResource::sl_user_passwordConfirmation.CommonResources::requireErrorMessage);
			$this->getControl()->equals($this->app->registerComplete(),"registerComplete");
			$this->getControl()->equals(Model::getErrorMessage(),implode($list,"<br/>"));
			$this->app->authInit();
			//フォーマットチェック
			Sl_user::setupSecurity("5 minute", ($model=new Sl_user()));
			$_POST[Sl_user::parseFormName(Sl_user::id)]="あああ";
			$_POST[Sl_user::parseFormName(Sl_user::password)]="ああああああ";
			$_POST[Sl_user::parseFormName(Sl_user::consentCheck)]="aaaaa";
			$list=array(ModelResource::sl_user_id.CommonResources::validationErrorCtypeAlnum_bar,
				ModelResource::sl_user_password.CommonResources::validationErrorCtypeAlnum,
				ModelResource::sl_user_consentCheck.CommonResources::validationEquals,
				ModelResource::sl_user_passwordConfirmation.CommonResources::requireErrorMessage);
			$this->getControl()->equals($this->app->registerComplete(),"registerComplete");
			$this->getControl()->equals(Model::getErrorMessage(),implode($list,"<br/>"));
			$this->app->authInit();
			//セキュリティーエラー
			$_POST[Sl_user::parseFormName(Sl_user::id)]="aaaaaaa";
			$_POST[Sl_user::parseFormName(Sl_user::password)]=TestUser::testPasswordValue;
			$_POST[Sl_user::parseFormName(Sl_user::consentCheck)]=Sl_user::consentCheck_equals;
			$_POST[Sl_user::parseFormName(Sl_user::passwordConfirmation)]="aaaa";
			$this->getControl()->equals($this->app->registerComplete(),"registerComplete");
			$this->getControl()->equals(Model::getErrorMessage(),CommonResources::nullCharacter);
			$this->app->authInit();
			//照合チェックパスワード
			Sl_user::setupSecurity("5 minute", ($model=new Sl_user()));
			$this->getControl()->equals($this->app->registerComplete(),"registerComplete");
			$this->getControl()->equals(Model::getErrorMessage(),TopErrorMessage::getCheckPasswordConfirmation(ModelResource::sl_user_passwordConfirmation));
			$this->app->authInit();
			//照合チェックID
			Sl_user::setupSecurity("5 minute", ($model=new Sl_user()));
			$_POST[Sl_user::parseFormName(Sl_user::id)]=TestUser::id;
			$_POST[Sl_user::parseFormName(Sl_user::passwordConfirmation)]=TestUser::testPasswordValue;
			$this->getControl()->equals($this->app->registerComplete(),"registerComplete");
			$this->getControl()->equals(Model::getErrorMessage(),ErrorMessage::getCheckDuplication(ModelResource::sl_user_id));
			$this->app->authInit();
			//本登録
			//ログイン
			Sl_user::setupSecurity("5 minute", ($model=new Sl_user()));
			$_POST[Sl_user::parseFormName(Sl_user::id)]="sohara_10169022test";
			$this->getControl()->equals($this->app->registerComplete(),"registerComplete");
			$this->app->authInit();
			//認証成功
			$this->getControl()->equals($this->app->index(),null);
			Sl_user::delete($this->app->getDB(ControllerRunnable::basicDbIndex), $this->app->getUser());
			$_SESSION=array();
			$this->app->authInit();
			$_POST=array();
		}

		public function exitTest(){
			$this->app->exitSession();
			unset($_GET[AppConfig::appAccessIndex]);
		}
	}
?>