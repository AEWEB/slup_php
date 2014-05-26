<?php
class Model_test extends Lf_testCase{
	/**
	 * @var DBControler_mock
	 */
	public $frameControl;
	/**
	 * @var MySQLDriver
	 */
	public $db;


	/**
	 * (non-PHPdoc)
	 * @see Lf_testCase::create()
	 */
	public function create(){
		$this->frameControl= new DBControler_mock();
		$this->db=new MySQLDriver(new TestDBParameter(),$this->frameControl);
		$this->getControl()->equalsNull($this->frameControl->error);
	}
	public function testFind(){
		$model=My_sample_datas::find($this->db,My_sample_datas::createModel(array(My_sample_datas::id=>My_sample_datas::idValue)));
		$this->getControl()->equals(count($model),1);
		$this->getControl()->equals($model[0]->get(My_sample_datas::id),My_sample_datas::idValue);
		$this->getControl()->equals($model[0]->get(My_sample_datas::name),My_sample_datas::nameValue);
		$this->getControl()->equals($model[0]->get(My_sample_datas::mail),My_sample_datas::mailValue);
		$this->getControl()->equals($model[0]->get(My_sample_datas::tel),My_sample_datas::telValue);
	}
	public function testFindByCount(){
		$count=My_sample_datas::findByCount($this->db, My_sample_datas::createModel(array()));
		$model=My_sample_datas::find($this->db,My_sample_datas::createModel(array()));
		$this->getControl()->equals(count($model),(int)$count);
		$this->getControl()->equals(1,(int)My_sample_datas::findByCount($this->db,
			My_sample_datas::createModel(array(My_sample_datas::id=>My_sample_datas::idValue)),array(DBDriver::queryOptionIndex_projection=>My_sample_datas::createModel()->get(My_sample_datas::id))));
	}
	

	public function testInsert(){
		$model=new My_sample_datas();
		$this->getControl()->equalsTrue(My_sample_datas::insert($this->db, My_sample_datas::createModel(array(
			My_sample_datas::name=>My_sample_datas::nameValue,
			My_sample_datas::mail=>My_sample_datas::mailValue,
			My_sample_datas::tel=>My_sample_datas::telValue))));		
		$id=$this->db->getLastInsertId(My_sample_datas::createModel());
		$model=My_sample_datas::find($this->db,My_sample_datas::createModel(array(My_sample_datas::id=>$id)));
		$this->getControl()->equals(intval($model[0]->get(My_sample_datas::id)),$id);
		$this->getControl()->equals($model[0]->get(My_sample_datas::name),My_sample_datas::name);
		$this->getControl()->equals($model[0]->get(My_sample_datas::mail),My_sample_datas::mail);
		$this->getControl()->equals($model[0]->get(My_sample_datas::tel),My_sample_datas::tel);
	}
	public function testUpdate(){
		$this->db->startTransaction();
		$this->getControl()->equalsTrue(My_sample_datas::save($this->db,My_sample_datas::createModel(array(My_sample_datas::id=>My_sample_datas::idValue,
			My_sample_datas::name=>My_sample_datas::nameValue,My_sample_datas::mail=>"update",My_sample_datas::tel=>My_sample_datas::telValue))));
		$model=My_sample_datas::find($this->db,My_sample_datas::createModel(array(My_sample_datas::id=>My_sample_datas::idValue)));
		$this->getControl()->equals($model[0]->get(My_sample_datas::mail),"update");
		$this->db->rollback();
	}
	public function testDelete(){
		$this->db->startTransaction();
		My_sample_datas::delete($this->db,My_sample_datas::createModel(array(My_sample_datas::id=>My_sample_datas::idValue)));
		$model=My_sample_datas::find($this->db, My_sample_datas::createModel(array(My_sample_datas::id=>My_sample_datas::idValue)));
		$this->getControl()->equals(count($model),0);
		$this->db->rollback();
	}
	

	public function testIsNumber(){
		$param=array(ModelRunnable::numMinIndex=>10,
			ModelRunnable::numMaxIndex=>20);
		$this->getControl()->equals(Model::isNumber(9,$param),ErrorMessage::getCheckNumMinError(10));
		$this->getControl()->equals(Model::isNumber(21,$param),ErrorMessage::getCheckNumMaxError(20));
		$this->getControl()->equalsNull(Model::isNumber(9,array()));
		$this->getControl()->equalsNull(Model::isNumber(21,array()));
		$this->getControl()->equalsNull(Model::isNumber(15,array()));
	}
	public function testIsValueLen(){
		$param=array(ModelRunnable::numMinIndex=>10,
			ModelRunnable::numMaxIndex=>20);
		$this->getControl()->equals(Model::isValueLen("aaaaaaaaa",$param),ErrorMessage::getCheckMinLength(10));
		$this->getControl()->equals(Model::isValueLen("aaaaaaaaaaaaaaaaaaaaa",$param),ErrorMessage::getCheckMaxLength(20));
		$this->getControl()->equalsNull(Model::isValueLen("aaaaaaaaa",array()));
		$this->getControl()->equalsNull(Model::isValueLen("aaaaaaaaaaaaaaaaaaaaa",array()));
		$this->getControl()->equalsNull(Model::isValueLen("aaaaaaaaaaaaaa",$param));
	}
	public function testIsValidation_numeric(){
		$param=array(ModelRunnable::numMinIndex=>10,
			ModelRunnable::numMaxIndex=>20);
		$this->getControl()->equals(Model::isValidation_numeric(9,$param),ErrorMessage::getCheckNumMinError(10));
		$this->getControl()->equals(Model::isValidation_numeric(21,$param),ErrorMessage::getCheckNumMaxError(20));
		$this->getControl()->equalsNull(Model::isValidation_numeric(9,array()));
		$this->getControl()->equalsNull(Model::isValidation_numeric(21,array()));
		$this->getControl()->equalsNull(Model::isValidation_numeric("10.5",$param));
		$this->getControl()->equals(Model::isValidation_numeric("21aaa",$param),CommonResources::validationErrorNumeric);
	}
	public function testIsValidation_integer(){
		$param=array(ModelRunnable::numMinIndex=>10,ModelRunnable::numMaxIndex=>20);
		$this->getControl()->equals(Model::isValidation_integer(9,$param),ErrorMessage::getCheckNumMinError(10));
		$this->getControl()->equals(Model::isValidation_integer(21,$param),ErrorMessage::getCheckNumMaxError(20));
		$this->getControl()->equalsNull(Model::isValidation_integer(9,array()));
		$this->getControl()->equalsNull(Model::isValidation_integer(21,array()));
		$this->getControl()->equals(Model::isValidation_integer(10.5,$param),CommonResources::validationErrorInteger);
	}
	public function testIsValidation_ctypeAlnum(){
		$param=array(ModelRunnable::numMinIndex=>10,ModelRunnable::numMaxIndex=>20);
		$this->getControl()->equals(Model::isValidation_ctypeAlnum("eio16",$param),ErrorMessage::getCheckMinLength(10));
		$this->getControl()->equals(Model::isValidation_ctypeAlnum("eiaaa16aaaaaaaaaaaaaaaaaaaaaaa",$param),ErrorMessage::getCheckMaxLength(20));
		$this->getControl()->equalsNull(Model::isValidation_ctypeAlnum("eio16",array()));
		$this->getControl()->equalsNull(Model::isValidation_ctypeAlnum("eiaaa16aaaaaaaaaaaaaaaaaaaaaaa",array()));
		$this->getControl()->equalsNull(Model::isValidation_ctypeAlnum("expseigml16co",$param));
		$this->getControl()->equals(Model::isValidation_ctypeAlnum("expseigma1_6",$param),CommonResources::validationErrorCtypeAlnum);
	}
	public function testIsValidation_alnum(){
		$param=array(ModelRunnable::numMinIndex=>10,ModelRunnable::numMaxIndex=>20);
		$this->getControl()->equals(Model::isValidation_alnum("eio",$param),ErrorMessage::getCheckMinLength(10));
		$this->getControl()->equals(Model::isValidation_alnum("eiaaaaaaaaaaaaaaaaaaaaaaaaaa",$param),ErrorMessage::getCheckMaxLength(20));
		$this->getControl()->equalsNull(Model::isValidation_alnum("eio",array()));
		$this->getControl()->equalsNull(Model::isValidation_alnum("eiaaaaaaaaaaaaaaaaaaaaaaaaaa",array()));
		$this->getControl()->equalsNull(Model::isValidation_alnum("expseigmailco",$param));
		$this->getControl()->equals(Model::isValidation_alnum("expseigma16",$param),CommonResources::validationErrorAlnum);
	}
	public function testIsValidation_ctypeAlnum_bar(){
		$param=array(ModelRunnable::numMinIndex=>10,ModelRunnable::numMaxIndex=>20);
		$this->getControl()->equals(Model::isValidation_ctypeAlnum_bar("ei--o",$param),ErrorMessage::getCheckMinLength(10));
		$this->getControl()->equals(Model::isValidation_ctypeAlnum_bar("ei--oei--oei--oei--oei--oei--o",$param),ErrorMessage::getCheckMaxLength(20));
		$this->getControl()->equalsNull(Model::isValidation_ctypeAlnum_bar("ei--o",array()));
		$this->getControl()->equalsNull(Model::isValidation_ctypeAlnum_bar("ei--oei--oei--oei--oei--oei--o",array()));
		$this->getControl()->equalsNull(Model::isValidation_ctypeAlnum_bar("exp_sei16gmailcom___",$param));
		$this->getControl()->equals(Model::isValidation_ctypeAlnum_bar("exp.sei16@gmail.com",$param),CommonResources::validationErrorCtypeAlnum_bar);
	}
	public function testIsValidation_mailAdd(){
		$param=array(ModelRunnable::numMinIndex=>10,ModelRunnable::numMaxIndex=>20);
		$this->getControl()->equals(Model::isValidation_mailAdd("ei6@gl.co",$param),ErrorMessage::getCheckMinLength(10));
		$this->getControl()->equals(Model::isValidation_mailAdd("sohara-others-3579@tempo.ocn.ne.jp",$param),ErrorMessage::getCheckMaxLength(20));
		$this->getControl()->equalsNull(Model::isValidation_mailAdd("ei6@gl.co",array()));
		$this->getControl()->equalsNull(Model::isValidation_mailAdd("sohara-others-3579@tempo.ocn.ne.jp",array()));
		$this->getControl()->equals(Model::isValidation_mailAdd("exp.sei16",$param),CommonResources::validationErrorMailAdd);
		$this->getControl()->equalsNull(Model::isValidation_mailAdd("exp.sei16@gmail.com",$param));
	}
	public function testIsValidation_url(){
		$param=array(ModelRunnable::numMinIndex=>10,ModelRunnable::numMaxIndex=>20);
		$this->getControl()->equals(Model::isValidation_url("http://a",$param),ErrorMessage::getCheckMinLength(10));
		$this->getControl()->equals(Model::isValidation_url("http://aaaaaaaaaaaaaaaaa",$param),ErrorMessage::getCheckMaxLength(20));
		$this->getControl()->equalsNull(Model::isValidation_url("http://a",array()));
		$this->getControl()->equalsNull(Model::isValidation_url("http://aaaaaaaaaaaaaaaaa",array()));
		$this->getControl()->equalsNull(Model::isValidation_url("http://localhost/",$param));
		$this->getControl()->equals(Model::isValidation_url("exp.sei16@gmail.com",$param),CommonResources::validationErrorUrl);
	}

	/**
	 *  setupSecurity
	 */
	public function testIsValidation_security(){
		Model::setupSecurity("5 minute",($model=new My_sample_datas()));
		//値が不正
		$this->getControl()->equals(Model::isValidation_security($model->get(Model::security)."test",null),CommonResources::securityErrorMessage);
		//値が一致
		$this->getControl()->equalsNull(Model::isValidation_security($model->get(Model::security),null));
		//二回目以降のため不正となる
		$this->getControl()->equals(Model::isValidation_security($model->get(Model::security),null),CommonResources::securityErrorMessage);
		$this->resetForm();
		//時間が不正
		Model::setupSecurity("-5 minute",$model);
		$this->getControl()->equals(Model::isValidation_security($model->get(Model::security),null),CommonResources::securityErrorMessage);
		//どちらかが格納されていない
		Model::setupSecurity("5 minute",$model);
		unset($_SESSION[My_sample_datas::getSecurityKeyName_test().ModelRunnable::sessionSecurity_value]);
		$this->getControl()->equals(Model::isValidation_security($model->get(Model::security),null),CommonResources::securityErrorMessage);
		Model::setupSecurity("5 minute",$model);
		unset($_SESSION[My_sample_datas::getSecurityKeyName_test().ModelRunnable::sessionSecurity_time]);
		$this->getControl()->equals(Model::isValidation_security($model->get(Model::security),null),CommonResources::securityErrorMessage);
		$this->resetForm();
		//フラグを元に戻す
		My_sample_datas::setSessionParamFlag(false);
		Model::setupSecurity("5 minute",($model=new My_sample_datas()));
		$this->getControl()->equalsNull(Model::isValidation_security($model->get(Model::security),false));
		$this->resetForm();
		My_sample_datas::setSessionParamFlag(false);
	}
	
	/**
	 * getErrorMessage,resetError
	 */
	public function testAddErrorMessageList() {
		Model::addErrorMessageList(($errorMessage="test"), array());
		Model::addErrorMessageList($errorMessage, array(ModelRunnable::outputIndex=>($output="output")));
		Model::addErrorMessageList($errorMessage, array(ModelRunnable::outputIndex=>($output2="output2")));
		$this->getControl()->equals(Model::getErrorMessage(),$output.$errorMessage."<br/>".$output2.$errorMessage);
		Model::resetError();
		$this->getControl()->equals(Model::getErrorMessage(),CommonResources::nullCharacter);
		Model::resetError();
	}
	
	/**
	 * isFirstAccess,resetErrorItem
	 */
	public function testIsValidation(){
		$this->getControl()->equalsTrue(Model::isValidation());
		Model::addErrorItemList("id");
		Model::addErrorMessageList(($errorMessage="test"), array(ModelRunnable::outputIndex=>($output="output")));
		$this->getControl()->equals(Model::isValidation(),false);
		$this->getControl()->equals(Model::getErrorMessage(),$output.$errorMessage);		
		Model::addErrorItemList("security");
		$this->getControl()->equals(Model::isValidation(),false);
		$this->getControl()->equals(Model::getErrorMessage(),CommonResources::nullCharacter);
		Model::resetError();
		Model::resetErrorItem();
		$this->getControl()->equalsTrue(Model::isValidation());
		Model::resetError();
		Model::resetErrorItem();
	}
	
	public function testRunValidation(){
		//文字数足りない
		$param=array(ModelRunnable::numMinIndex=>10,ModelRunnable::numMaxIndex=>20,Model::outputIndex=>($output="output"));
		My_sample_datas::runValidation_test(($errorItem="test"),$param,"test");
		$this->getControl()->equals(Model::isErrorItem($errorItem),false);
		$this->getControl()->equals(Model::getErrorMessage(),$output.ErrorMessage::getCheckMinLength(10));
		$param=array(ModelRunnable::numMinIndex=>10,ModelRunnable::numMaxIndex=>20,Model::outputIndex=>($output2="output2"),
			Model::typeIndex=>Model::validation_ctypeAlnum);
		//成功
		My_sample_datas::runValidation_test(($errorItem2="test2"),$param,"testtesttesttest");
		$this->getControl()->equalsTrue(Model::isErrorItem($errorItem2));
		$this->getControl()->equals(Model::getErrorMessage(),$output.ErrorMessage::getCheckMinLength(10));
		//英数字以外
		My_sample_datas::runValidation_test($errorItem2,$param,"testtesttestテスト");
		$this->getControl()->equals(Model::isErrorItem($errorItem2),false);
		$this->getControl()->equals(Model::getErrorMessage(),$output.ErrorMessage::getCheckMinLength(10)."<br/>".$output2.CommonResources::validationErrorCtypeAlnum);
		$this->resetForm();
	}
	
	public function testGenerateForm(){
		$model=new My_sample_datas();
		$value="values_a";
		$model->set(My_sample_datas::id,$value);
		$this->getControl()->equals(htmlspecialchars(HtmlHelper::text("my_sample_datas_id",$value, 
			array()),ENT_QUOTES,AppConfig::character),				
			htmlspecialchars(My_sample_datas::generateForm_test("id", array(ModelRunnable::formIndex=>"text"),$model)
			,ENT_QUOTES,AppConfig::character));
		$this->getControl()->equals(htmlspecialchars(HtmlHelper::textArea("my_sample_datas_id",$value,
				array()),ENT_QUOTES,AppConfig::character),
			htmlspecialchars(My_sample_datas::generateForm_test("id", array(ModelRunnable::formIndex=>"textArea"),$model)
				,ENT_QUOTES,AppConfig::character));
	}
	
	public function testSetParam(){
		$param=array(ModelRunnable::outputIndex=>($output="output"),ModelRunnable::requireIndex=>true);
		$model=new My_sample_datas();
		$testParam="testParam";
		//必須入力チェックアリ
		My_sample_datas::setParam_test($param,$testParam,$model);
		$this->getControl()->equals($model->get($testParam),CommonResources::nullCharacter);
		$this->getControl()->equals(Model::isErrorItem($testParam),false);
		$this->getControl()->equals(Model::getErrorMessage(),$output.CommonResources::requireErrorMessage);
		$this->resetForm();
		//必須入力チェックなし
		$param=array(ModelRunnable::outputIndex=>($output="output"));
		My_sample_datas::setParam_test($param,$testParam,$model);
		$this->getControl()->equals($model->get($testParam),CommonResources::nullCharacter);
		$this->getControl()->equalsTrue(Model::isErrorItem($testParam));
		$this->getControl()->equals(Model::getErrorMessage(),CommonResources::nullCharacter);
		$this->resetForm();
		//英数字
		$_POST[My_sample_datas::parseFormName($testParam)]=($testValue="testValue__");
		$param=array(ModelRunnable::numMinIndex=>10,ModelRunnable::numMaxIndex=>20,ModelRunnable::outputIndex=>($output="output"),ModelRunnable::typeIndex=>"ctypeAlnum");
		My_sample_datas::setParam_test($param,$testParam,$model);
		$this->getControl()->equals($model->get($testParam),$testValue);
		$this->getControl()->equals(Model::isErrorItem($testParam),false);
		$this->getControl()->equals(Model::getErrorMessage(),$output.CommonResources::validationErrorCtypeAlnum);
		$this->resetForm();
		
	}
	public function testFormCheck(){
		//バリデーションエラー、最初のアクセス
		$_POST[My_sample_datas::parseFormName(My_sample_datas::id)]=My_sample_datas::idValue;
		$_POST[My_sample_datas::parseFormName(My_sample_datas::name)]=My_sample_datas::nameValue;
		$_POST[My_sample_datas::parseFormName(My_sample_datas::mail)]=My_sample_datas::mailValue;
		$_POST[My_sample_datas::parseFormName(My_sample_datas::tel)]=My_sample_datas::telValue;
		$model=My_sample_datas::formCheck();
		$this->getControl()->equals($model->get(My_sample_datas::id),My_sample_datas::idValue);
		$this->getControl()->equals($model->get(My_sample_datas::name),My_sample_datas::nameValue);
		$this->getControl()->equals($model->get(My_sample_datas::mail),My_sample_datas::mailValue);
		$this->getControl()->equals($model->get(My_sample_datas::tel),My_sample_datas::telValue);
		$this->getControl()->equalsTrue(Model::isErrorItem(My_sample_datas::id));
		$this->getControl()->equalsTrue(Model::isErrorItem(My_sample_datas::name));
		$this->getControl()->equals(Model::isErrorItem(My_sample_datas::mail),false);
		$this->getControl()->equalsTrue(Model::isErrorItem(My_sample_datas::tel));
		$this->getControl()->equals(Model::isErrorItem(My_sample_datas::security),false);
		$this->getControl()->equals(Model::getErrorMessage(),My_sample_datas::mailOutput.CommonResources::validationErrorMailAdd);
		$this->getControl()->equals(My_sample_datas::isValidation(),false);
		$this->getControl()->equals(count(My_sample_datas::getErrorItemList()),2);
		$this->getControl()->equals(My_sample_datas::getErrorMessage(),CommonResources::nullCharacter);
		$this->resetForm();
		//セキュリティーをセットしない
		$_POST[My_sample_datas::parseFormName(My_sample_datas::id)]=My_sample_datas::idValue;
		$_POST[My_sample_datas::parseFormName(My_sample_datas::name)]=My_sample_datas::nameValue;
		$_POST[My_sample_datas::parseFormName(My_sample_datas::mail)]=($mailValue="exp.sei16@gmail.com");
		$_POST[My_sample_datas::parseFormName(My_sample_datas::tel)]=($telValue="09000000000");
		$model=My_sample_datas::formCheck();
		$this->getControl()->equals($model->get(My_sample_datas::id),My_sample_datas::idValue);
		$this->getControl()->equals($model->get(My_sample_datas::name),My_sample_datas::nameValue);
		$this->getControl()->equals($model->get(My_sample_datas::mail),$mailValue);
		$this->getControl()->equals($model->get(My_sample_datas::tel),$telValue);
		$this->getControl()->equalsTrue(Model::isErrorItem(My_sample_datas::id));
		$this->getControl()->equalsTrue(Model::isErrorItem(My_sample_datas::name));
		$this->getControl()->equalsTrue(Model::isErrorItem(My_sample_datas::mail));
		$this->getControl()->equalsTrue(Model::isErrorItem(My_sample_datas::tel));
		$this->getControl()->equals(Model::isErrorItem(My_sample_datas::security),false);
		$this->getControl()->equals(Model::getErrorMessage(),CommonResources::nullCharacter);
		$this->getControl()->equals(My_sample_datas::isValidation(),false);
		$this->getControl()->equals(count(My_sample_datas::getErrorItemList()),1);
		$this->getControl()->equals(My_sample_datas::getErrorMessage(),CommonResources::nullCharacter);
		//セキュリティーセット
		$this->resetForm();
		My_sample_datas::setupSecurity("5 minute", $model);
		$_POST[My_sample_datas::parseFormName(My_sample_datas::id)]=My_sample_datas::idValue;
		$_POST[My_sample_datas::parseFormName(My_sample_datas::name)]=My_sample_datas::nameValue;
		$_POST[My_sample_datas::parseFormName(My_sample_datas::mail)]=$mailValue;
		$_POST[My_sample_datas::parseFormName(My_sample_datas::tel)]=$telValue;
		$_POST[My_sample_datas::parseFormName(My_sample_datas::security)]=$model->get(ModelRunnable::security);
		$this->getControl()->equalsTrue(Model::isErrorItem(My_sample_datas::id));
		$this->getControl()->equalsTrue(Model::isErrorItem(My_sample_datas::name));
		$this->getControl()->equalsTrue(Model::isErrorItem(My_sample_datas::mail));
		$this->getControl()->equalsTrue(Model::isErrorItem(My_sample_datas::tel));
		$this->getControl()->equalsTrue(Model::isErrorItem(My_sample_datas::security));
		$this->getControl()->equals(Model::getErrorMessage(),CommonResources::nullCharacter);
		$this->getControl()->equalsTrue(My_sample_datas::isValidation());
		$this->getControl()->equals(count(My_sample_datas::getErrorItemList()),0);
		$this->getControl()->equals(My_sample_datas::getErrorMessage(),CommonResources::nullCharacter);
		$this->resetForm();
		My_sample_datas::setSessionParamFlag(false);
	}
	
	
	
	
	
	
	
	
	protected function resetForm(){
		Model::resetError();
		Model::resetErrorItem();
		$_POST=array();
		$_SESSION=array();
	}
}

















?>