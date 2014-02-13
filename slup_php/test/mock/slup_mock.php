<?php
	class DBControler_mock implements DBController{
		public $error;
		public function queryError($error,$query){
			$this->error=$error.$query;
		}
		public function initMock(){
			$this->error=null;
		}
	}

	class Controller_mock extends Controller{
	
		public function getRootList(){
			return array("test"=>"test");
		}
		
		public function setup_test(){
			$this->setup();
		}
		public function setupSessionData_test(){
			$this->setupSessionData();
		}
		public function setUser_test($user){
			$this->setUser($user);
		}
		public function getTrueFinger_test(){
			return $this->getTrueFinger();
		}
		public function isFingerprint_test($finger){
			return $this->isFingerprint($finger);
		}
		public function registerSessionUser_test(){
			return $this->registerSessionUser();
		}
		
		public function getAuthDB_test(){
			return $this->getAuthDB();
		}
		public function analysisAction_test(){
			return $this->analysisAction();
		}
		public function getActionParam_test(){
			return $this->getActionParam();
		}
		public function getAppUrl_test(){
			return $this->getAppUrl();
		}
	}
	
?>