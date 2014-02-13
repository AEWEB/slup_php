<?php
class DatabaseParameter_test extends Lf_testCase{
	private $dbParameter;
	public function create(){
		$this->dbParameter=new DatabaseParameter("serverName","userName","password","dbName", "caracterCode");
	}
	public function testGetServerName(){
		$this->getControl()->equals($this->dbParameter->getServerName(),"serverName");
	}
	public function testGetUserName(){
		$this->getControl()->equals($this->dbParameter->getUserName(),"userName");
	}
	public function testGetPassword(){
		$this->getControl()->equals($this->dbParameter->getPassword(),"password");
	}
	public function testGetDbName(){
		$this->getControl()->equals($this->dbParameter->getDbName(),"dbName");
	}
	public function testGetCaracterCode(){
		$this->getControl()->equals($this->dbParameter->getCaracterCode(),"caracterCode");
	}
}
?>