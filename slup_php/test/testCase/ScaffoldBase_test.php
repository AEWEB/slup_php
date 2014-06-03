<?php
	require_once AppConfig::getAppPath()."scaffoldBase.php";
	class ScaffoldBase_mock extends ScaffoldBase{
		
		const showCount="20";
		
		protected function setupUseModel(){
			$this->setModel(new My_sample_datas());
		}
		protected function setupShowModels(){
			$this->setModels(My_sample_datas::find($this->getDB(self::basicDbIndex),My_sample_datas::createModel(array()),
				array(DBDriver::queryOptionIndex_order=>array(array(DBDriver::queryOptionIndex_order_column=>My_sample_datas::id,DBDriver::queryOptionIndex_order_value=>DBDriver::desc)),
					DBDriver::queryOptionIndex_limitStart=>$this->getPager(),DBDriver::queryOptionIndex_limitCount=>self::showCount)));
		}
		protected function setupEditModel(){
			$this->setModels(My_sample_datas::find($this->getDB(self::basicDbIndex),My_sample_datas::createModel(array(
				My_sample_datas::id=>$this->getModel()->get(My_sample_datas::id)))));
			if($this->getModel()->get(My_sample_datas::id)===null){
			}else if(count(($list=$this->getModels()))>0){
				$this->setModel($list[0]);
				return true;
			}
			return false;
		}
		protected function getModelsAllCount(){
			return My_sample_datas::findByCount($this->getDB(self::basicDbIndex), My_sample_datas::createModel(array()));
		}
		/**
		 * @return string
		 */
		protected function createSuccess(){
			$this->getModel()->set(My_sample_datas::id, null);
			My_sample_datas::insert($this->getDB(self::basicDbIndex), $this->getModel());
			$this->getModel()->set(My_sample_datas::id,
				$this->getDB(self::basicDbIndex)->getLastInsertId($this->getModel()));
			$this->setupEditModel();
			return "edit";
		}
		/**
		 * @return string
		 */
		protected function updateSuccess(){
			Sllearning::save($this->getDB(self::basicDbIndex),$this->getModel());
			$this->setupEditModel();
			return "edit";
		}
		/**
		 *@return string
		 */
		protected function destroySuccess(){
			My_sample_datas::delete($this->getDB(self::basicDbIndex), $this->getModel());
			$this->show();
			return "show";
		}
		
		/**
		 * mock methods
		 */
		public function init_param(){
			$this->setupUseModel();
			$this->setPager(null);
			$this->setModels(null);
			$this->setModelCount(0);
			$_POST=array();
			$_SESSION=array();
			$_GET=array();
			My_sample_datas::setSessionParamFlag(false);
			My_sample_datas::resetError();
			My_sample_datas::resetErrorItem();
			$list=My_sample_datas::getColumnArray();
			$list[My_sample_datas::id][ModelRunnable::requireIndex]=true;
			My_sample_datas::setColumnArray($list);
		}
		public function setupPagerParams_test(){
			$this->setupPagerParams();
		}
		public function setupModelId_test(){
			$this->setupModelId();
		}
		
		/**
		 * override
		 */
		public static function getControllerName() {
			return "ScaffoldBase";
		}
		protected function includeResource(){
			
		}
		public function createDBDriver(){
			$this->setDB(ControllerRunnable::basicDbIndex,new MySQLDriver(new TestDBParameter(),$this));
		}
		
		
	}
	class ScaffoldBase_test extends Lf_testCase{
		/**
		 * @var ScaffoldBase_mock
		 */
		private $app;
	
		public function create(){
			$this->app=new ScaffoldBase_mock();
			$this->app->init_param();
		}
		public function testSetupPagerParams(){
			$this->app->setupPagerParams_test();
			$this->getControl()->equals($this->app->getPager(),"0");
			$_GET[ScaffoldBase::pagerIndex]=($page="10");
			$this->app->setupPagerParams_test();
			$this->getControl()->equals($this->app->getPager(), $page);
			$this->app->init_param();
			$this->getControl()->equals($this->app->getPager(), null);
		}
		
		public function testSetupModelId(){
			$this->getControl()->equalsNull($this->app->getModel()->get(ModelRunnable::id));
			$this->app->setupModelId_test();
			$this->getControl()->equalsNull($this->app->getModel()->get(ModelRunnable::id));
			$_GET[ScaffoldBase::edit_idIndex]=($id="10");
			$this->app->setupModelId_test();
			$this->getControl()->equals($this->app->getModel()->get(ModelRunnable::id), $id);
			$this->app->init_param();
			$this->getControl()->equalsNull($this->app->getModel()->get(ModelRunnable::id));
		}
		
		public function testCreateAndShow(){
			//first display.
			$this->getControl()->equalsNull($this->app->create());
			$this->getControl()->equals(My_sample_datas::getErrorMessage(), CommonResources::nullCharacter);
			$this->app->init_param();	
			//security error.
			$_POST[My_sample_datas::mail]="aaa";
			$this->getControl()->equalsNull($this->app->create());
			$this->getControl()->equals(My_sample_datas::getErrorMessage(), CommonResources::nullCharacter);
			$this->app->init_param();
			//set security key.
			$_POST[My_sample_datas::parseFormName(My_sample_datas::mail)]="aaa";
			My_sample_datas::setupSecurity("5 minute", $this->app->getModel());
			$_POST[My_sample_datas::parseFormName(Model::security)]=$this->app->getModel()->get(ModelRunnable::security);
			$this->getControl()->equalsNull($this->app->create());
			$this->getControl()->equals(My_sample_datas::getErrorMessage(),My_sample_datas::mailOutput.CommonResources::validationErrorMailAdd);
			$this->app->init_param();
			//success insert.
			My_sample_datas::setupSecurity("5 minute", $this->app->getModel());
			$_POST[My_sample_datas::parseFormName(Model::security)]=$this->app->getModel()->get(ModelRunnable::security);
			$_POST[My_sample_datas::parseFormName(My_sample_datas::name)]="crAndS";
			$list=My_sample_datas::getColumnArray();
			$list[My_sample_datas::id][ModelRunnable::requireIndex]=false;
			My_sample_datas::setColumnArray($list);
			$this->getControl()->equals($this->app->create(),"edit");
			
			
			
			//セキュリティーキーをセット
			/**
			Sl_user::setupSecurity("5 minute", ($model=new Sl_user()));
			$_POST=array();
			$_POST[Sl_user::parseFormName(Model::security)]=$model->get(ModelRunnable::security);
			$this->getControl()->equals($this->app->index(),"login");
			$list=array(ModelResource::sl_user_id.CommonResources::requireErrorMessage,ModelResource::sl_user_password.CommonResources::requireErrorMessage);
			$this->getControl()->equals(Model::getErrorMessage(),implode($list,"<br/>"));
		**/
			
		}
		
	
		public function exitTest(){
			$this->app->exitSession();
			unset($_GET[AppConfig::appAccessIndex]);
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
	}
?>