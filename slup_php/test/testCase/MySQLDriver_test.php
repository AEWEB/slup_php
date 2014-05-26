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
	public function testConstructWhere(){
		$this->db->setup();
		//1つのみ
		$this->getControl()->equals($this->db->constructWhere(My_sample_datas::createModel(array(My_sample_datas::mail=>My_sample_datas::mailValue)))," where ".My_sample_datas::mail."='".My_sample_datas::mailValue."'");
		//3つ
		$this->getControl()->equals($this->db->constructWhere(My_sample_datas::createModel(array(My_sample_datas::id=>My_sample_datas::idValue,
			My_sample_datas::name=>My_sample_datas::nameValue,My_sample_datas::tel=>My_sample_datas::telValue))),
			" where ".My_sample_datas::id."='".My_sample_datas::idValue."' and ".My_sample_datas::name."='".My_sample_datas::nameValue."' and ".My_sample_datas::tel."='".My_sample_datas::telValue."'");
		//オプションが一つ
		$conditionOption=array();
		$conditionOption[]=array(DBDriver::queryOptionIndex_logic=>MySQLDriver::andSql,DBDriver::queryOptionIndex_val=>"test='test'");
		$this->getControl()->equals($this->db->constructWhere(My_sample_datas::createModel(array()),array(DBDriver::queryOptionIndex_condition=>$conditionOption)),
			" where test='test'");
		//オプションが二つ以上
		$conditionOption[]=array(DBDriver::queryOptionIndex_logic=>MySQLDriver::andSql,DBDriver::queryOptionIndex_val=>"test2='test2'");
		$conditionOption[]=array(DBDriver::queryOptionIndex_logic=>MySQLDriver::orSql,DBDriver::queryOptionIndex_val=>"test3='test3'");
		$this->getControl()->equals($this->db->constructWhere(My_sample_datas::createModel(array()),array(DBDriver::queryOptionIndex_condition=>$conditionOption)),
				" where test='test' and test2='test2' or test3='test3'");
		//カラム3つオプション3つ
		$conditionOption[0][DBDriver::queryOptionIndex_logic]=MySQLDriver::orSql;
		$this->getControl()->equals($this->db->constructWhere(My_sample_datas::createModel(array(My_sample_datas::id=>My_sample_datas::idValue,
			My_sample_datas::name=>My_sample_datas::nameValue,My_sample_datas::tel=>My_sample_datas::telValue)),array(DBDriver::queryOptionIndex_condition=>$conditionOption)),
			" where ".My_sample_datas::id."='".My_sample_datas::idValue."' and ".My_sample_datas::name."='".My_sample_datas::nameValue."' and ".My_sample_datas::tel."='".My_sample_datas::telValue."'".
			" or test='test' and test2='test2' or test3='test3'");
		//null
		$this->getControl()->equals($this->db->constructWhere(My_sample_datas::createModel(array()),array()),CommonResources::nullCharacter);
	}
	public function testConstructOrder(){
		//なし
		$this->getControl()->equals($this->db->constructOrder(My_sample_datas::createModel(array())), CommonResources::nullCharacter);
		//オーダーby句
		$this->getControl()->equals($this->db->constructOrder(My_sample_datas::createModel(array()),
			array(DBDriver::queryOptionIndex_order=>"date desc"))," order by date desc");
	}
	public function testConstructProjection(){
		//なし
		$this->getControl()->equals($this->db->constructProjection(My_sample_datas::createModel(array())), "*");
		//射影有
		$this->getControl()->equals($this->db->constructProjection(My_sample_datas::createModel(array()),
				array(DBDriver::queryOptionIndex_projection=>"id,tel")),"id,tel");
	}
	public function testConstructLimit(){
		//なし
		$this->getControl()->equals($this->db->constructLimit(My_sample_datas::createModel(array())), CommonResources::nullCharacter);
		//start有
		$this->getControl()->equals($this->db->constructLimit(My_sample_datas::createModel(array()),
			array(DBDriver::queryOptionIndex_limitStart=>"0")), CommonResources::nullCharacter);
		//count有
		$this->getControl()->equals($this->db->constructLimit(My_sample_datas::createModel(array()),
			array(DBDriver::queryOptionIndex_limitCount=>"0")), CommonResources::nullCharacter);
		//limit有
		$this->getControl()->equals($this->db->constructLimit(My_sample_datas::createModel(array()),
			array(DBDriver::queryOptionIndex_limitStart=>"0",DBDriver::queryOptionIndex_limitCount=>"10")), 
			" limit 0,10");
	}
	
	
	public function testGetSelectModel(){		
		$this->db->setup();
		$model=$this->db->getSelectModel(Sl_user::createModel(array(Sl_user::id=>TestUser::id)));
		$this->getControl()->equals(count($model),1);
		$this->getControl()->equals($model[0]->get(Sl_user::id),TestUser::id);
	}
	
	public function testInsert(){
		$this->db2->startTransaction();
		//データなし
		$this->getControl()->equals($this->db2->insert(My_sample_datas::createModel(array())), false);
		//カラム不足
		$this->getControl()->equals($this->db2->insert(
			My_sample_datas::createModel(array(
				My_sample_datas::mail=>My_sample_datas::mailValue,
				My_sample_datas::tel=>My_sample_datas::telValue))),false);
		//全データ投入
		$this->getControl()->equalsTrue($this->db2->insert(
			My_sample_datas::createModel(array(
				My_sample_datas::name=>My_sample_datas::nameValue,
				My_sample_datas::mail=>My_sample_datas::mailValue,
				My_sample_datas::tel=>My_sample_datas::telValue))));
		$id=$this->db2->getLastInsertId(My_sample_datas::createModel());
		$model=$this->db2->getSelectModel(My_sample_datas::createModel(array(My_sample_datas::id=>$id)));
		$this->getControl()->equals(intval($model[0]->get(My_sample_datas::id)),$id);	
		$this->db2->commit();
	}
	public function testDelete(){
		$this->db2->startTransaction();
		$this->getControl()->equalsTrue($this->db2->delete(($model=My_sample_datas::createModel(array(My_sample_datas::id=>My_sample_datas::idValue)))));
		$model=$this->db2->getSelectModel($model);
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
		$model=My_sample_datas::createModel(array(My_sample_datas::id=>My_sample_datas::idValue));
		$list[DBDriver::queryOptionIndex_update][My_sample_datas::mail]="update";
		$list[DBDriver::queryOptionIndex_update][My_sample_datas::name]="update";
		$this->getControl()->equalsTrue($this->db2->update($model,$list));
		$model=$this->db2->getSelectModel($model);
		$this->getControl()->equals($model[0]->get(My_sample_datas::mail),"update");
		$this->getControl()->equals($model[0]->get(My_sample_datas::name),"update");
		$this->db2->rollback();
	}
}
?>