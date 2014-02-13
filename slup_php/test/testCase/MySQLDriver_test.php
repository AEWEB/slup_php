<?php
	class MySQLDriver_test extends Lf_testCase{
		/**
		 * @var DBControler_mock
		 */
		public $frameControl;
		/**
		 * @var MySQLDriver
		 */
		public $db;
		/**
		 * @var MySQLDriver
		 */
		private $db2;

	/**
	 * (non-PHPdoc)
	 * @see Lf_testCase::create()
	 */
	public function create(){
		global $dbParameter_0;
		$this->frameControl= new DBControler_mock();
		//コネクション確立に失敗させる
		$this->db=new MySQLDriver(new DatabaseParameter("test",$dbParameter_0->getUserName(),$dbParameter_0->getPassword(),$dbParameter_0->getDbName(),$dbParameter_0->getCaracterCode()),$this->frameControl);
		$this->getControl()->equals($this->frameControl->error,MySQLDriver::failedSetupErrorWord);
		$this->frameControl->initMock();
		$this->db=new MySQLDriver(new DatabaseParameter($dbParameter_0->getServerName(),"test",$dbParameter_0->getPassword(),$dbParameter_0->getDbName(),$dbParameter_0->getCaracterCode()),$this->frameControl);
		$this->getControl()->equals($this->frameControl->error,MySQLDriver::failedSetupErrorWord);
		$this->frameControl->initMock();
		$this->db=new MySQLDriver(new DatabaseParameter($dbParameter_0->getServerName(),$dbParameter_0->getUserName(),"test",$dbParameter_0->getDbName(),$dbParameter_0->getCaracterCode()),$this->frameControl);
		$this->getControl()->equals($this->frameControl->error,MySQLDriver::failedSetupErrorWord);
		$this->frameControl->initMock();
		$this->db=new MySQLDriver(new DatabaseParameter($dbParameter_0->getServerName(),$dbParameter_0->getUserName(),$dbParameter_0->getPassword(),"aaa",$dbParameter_0->getCaracterCode()),$this->frameControl);
		$this->getControl()->equals($this->frameControl->error,MySQLDriver::failedSetupErrorWord);
		$this->frameControl->initMock();
		$this->db=new MySQLDriver(new DatabaseParameter($dbParameter_0->getServerName(),$dbParameter_0->getUserName(),$dbParameter_0->getPassword(),$dbParameter_0->getDbName(),"test"),$this->frameControl);
		$this->getControl()->equals($this->frameControl->error,MySQLDriver::failedSetupErrorWord);
		$this->frameControl->initMock();
		//コネクションに成功させる
		$this->db=new MySQLDriver($dbParameter_0,$this->frameControl);
		$this->db2=new MySQLDriver(new TestDBParameter(),$this->frameControl);
		$this->getControl()->equalsNull($this->frameControl->error);
	}

	public function testSelect(){//照会テスト
		$this->db->setup();
		$this->frameControl->initMock();
		//照会成功
		$select=$this->db->select("select * from ".Sl_user::getTable()." where ".Sl_user::createModel()->get(Sl_user::id)."='".TestUser::id."'");
		$this->getControl()->equals($select[0][Sl_user::createModel()->get(Sl_user::id)],TestUser::id);
		$this->getControl()->equalsNull($this->frameControl->error);
		//照会失敗
		try{
			$selectWord="select * from ".Sl_user::getTable()." where hoge='".TestUser::id."'";
			$select=$this->db->select($selectWord);
		}catch(Exception $e){
		}
		$this->getControl()->equals($this->frameControl->error,MySQLDriver::selectErrorWord.$selectWord);
	}
	
	public function testTwoDB(){//二つのDBにアクセスする
		$this->db2->setup();
		$db1Select="select * from ".Sl_user::getTable()." where ".Sl_user::createModel()->get(Sl_user::id)."='".TestUser::id."'";
		$this->db2=new MySQLDriver(new TestDBParameter(),$this->frameControl);
		$db2Select="select * from ".My_sample_datas::getTable()." where ".My_sample_datas::createModel()->get(My_sample_datas::id)."='".My_sample_datas::idValue."'";
		try{//db1のコネクションに失敗させる
			$select=$this->db->select($db1Select);
		}catch(Exception $e){
		}
		$this->getControl()->equals($this->frameControl->error,MySQLDriver::selectErrorWord.$db1Select);
		//db2のコネクションに成功させる
		$this->frameControl->initMock();
		$select=$this->db2->select($db2Select);
		$this->getControl()->equals($select[0][My_sample_datas::createModel()->get(My_sample_datas::name)], My_sample_datas::nameValue);
		$this->getControl()->equalsNull($this->frameControl->error);
		//db1のコネクションに成功させる
		$this->frameControl->initMock();
		$this->db->setup();
		$select=$this->db->select($db1Select);
		$this->getControl()->equals($select[0][Sl_user::createModel()->get(Sl_user::id)], TestUser::id);
		$this->getControl()->equalsNull($this->frameControl->error);
		//db2のコネクションに失敗させる
		try{//db1のコネクションに失敗させる
			$select=$this->db2->select($db2Select);
		}catch(Exception $e){
		}
		$this->getControl()->equals($this->frameControl->error,MySQLDriver::selectErrorWord.$db2Select);
		//db2のクエリ-をロールバックさせる
		$db2Query="update ".My_sample_datas::getTable()." set ".My_sample_datas::createModel()->get(My_sample_datas::name)."='test' where ".
			My_sample_datas::createModel()->get(My_sample_datas::id)."='".My_sample_datas::idValue."'";
		$this->db2->startTransaction();
		$this->db2->query($db2Query);
		try{//db1のコネクションに失敗させる
			$select=$this->db->select($db1Select);
		}catch(Exception $e){
		}
		$this->getControl()->equals($this->frameControl->error,MySQLDriver::selectErrorWord.$db1Select);
		//再びdb2を照会して、ロールバックする
		$select=$this->db2->select($db2Select);
		$this->getControl()->equals($select[0][My_sample_datas::createModel()->get(My_sample_datas::name)], "test");
		$this->db2->rollback();
		$select=$this->db2->select($db2Select);
		$this->getControl()->equals($select[0][My_sample_datas::createModel()->get(My_sample_datas::name)],My_sample_datas::nameValue);
	}
	
	public function testGetSelectModel(){
		$this->db->setup();
		$columModel=Sl_user::createModel();
		$model=$this->db->getSelectModel($columModel, null,$columModel->createModel()->get(Sl_user::id)."='".TestUser::id."'");
		$this->getControl()->equals(count($model),1);
		$this->getControl()->equals($model[0]->get(Sl_user::id),TestUser::id);
	}
	public function testInsert(){
		$this->db2->startTransaction();
		$columnModel=My_sample_datas::createModel();
		$this->getControl()->equalsTrue($this->db2->insert($columnModel,
				array("null,'".My_sample_datas::nameValue."','".My_sample_datas::mailValue."','".My_sample_datas::telValue."'")));
		
		$id=$this->db2->getLastInsertId($columnModel);
		$model=$this->db2->getSelectModel($columnModel,null,$columnModel->get(My_sample_datas::id)."=".$id);
		$this->getControl()->equals(intval($model[0]->get(My_sample_datas::id)),$id);
		
		$this->db2->commit();
	}
	
	public function testDelete(){
		$this->db2->startTransaction();
		$columnModel=My_sample_datas::createModel();
		$this->getControl()->equalsTrue($this->db2->delete($columnModel,$columnModel->get(My_sample_datas::id)."='".My_sample_datas::idValue."'"));
		$model=$this->db2->getSelectModel($columnModel,null,$columnModel->get(My_sample_datas::id)."='".My_sample_datas::idValue."'");
		$this->getControl()->equals(count($model),0);
		$this->db2->rollback();
	}
	public function testQuery(){
		$this->db2->startTransaction();
		$this->db2->query("aaa");
		$this->getControl()->equals($this->frameControl->error,MySQLDriver::queryErrorWord."aaa");
		$this->db2->rollback();
	}
	public function testUpdate(){
		$this->db2->startTransaction();
		$columnModel=My_sample_datas::createModel();
		$this->getControl()->equalsTrue($this->db2->update($columnModel,array($columnModel->get(My_sample_datas::mail)."='update'"),
				$columnModel->get(My_sample_datas::id)."=".My_sample_datas::idValue));
		$model=$this->db2->getSelectModel($columnModel,null,$columnModel->get(My_sample_datas::id)."=".My_sample_datas::idValue);
		$this->getControl()->equals($model[0]->get(My_sample_datas::mail),"update");
		$this->db2->rollback();
	}
}
?>