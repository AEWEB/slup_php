<?php
/**
 * Class for MySql driver
 */
class MySQLDriver implements DBDriver{
	/**
	 * Error message when it fails to connect db
	 * db接続に失敗した場合のエラーメッセージ
	 */
	const failedSetupErrorWord = "Failed to set up for db.";
	/**
	 * Error when it fail to query.
	 * クエリーが失敗した時のエラーメッセージ
	 */
	const queryErrorWord  = "Failed to query::";
	/**
	 * Error when it fail to select.
	 * クエリーが失敗した時のエラーメッセージ
	 */
	const selectErrorWord  = "Failed to select::";
	/**
	 * db connection.
	 * @var String
	 */
	private $connect;
	/**
	 * flag for transaction.
	 * トランザクションのフラグ
	 * @var boolean
	 */
	private $transactionFlag;
	/**
	 * @var DBController
	 */
	private $controller;
	/**
	 * @var DatabaseParameter
	 */
	private $parameter;

	/**
	 * constructor
	 */
	public function MySQLDriver($parameter,$controller){
		$this->parameter=$parameter;
		$this->controller=$controller;
		$this->init();
	}
	/**
	 * @return void
	 */
	protected function init(){
		try{
			if(!($this->connect=mysql_connect(
					$this->getParameter()->getServerName(),
					$this->getParameter()->getUserName(),
					$this->getParameter()->getPassword()))){
			}else if(!$this->setup()){
			}else if(mysql_query("SET NAMES ".
					$this->getParameter()->getCaracterCode())){
				return;
			}
		}catch(Exception $e){
		}
		$this->getControl()->queryError(self::failedSetupErrorWord,"");
	}
	/**
	 * @return DBController
	 */
	protected function getControl() {
		return $this->controller;
	}
	/**
	 * @return DatabaseParameter
	 */
	protected function getParameter(){
		return $this->parameter;
	}

	/**
	 * implementention ----DBDriver
	 */
	public function startTransaction(){
		$this->setup();
		mysql_query("START TRANSACTION",$this->connect);
		$this->transactionFlag=true;
	}
	public function commit(){
		mysql_query("commit",$this->connect);
		$this->transactionFlag=false;
	}
	public function rollback(){
		mysql_query("rollback",$this->connect);
		$this->transactionFlag=false;
	}
	public function query($queryWord){
		print($queryWord);
		if(!mysql_query($queryWord)){//Query result is invalid 問い合わせ結果が不正
			if($this->transactionFlag){//If a transaction has been started.トランザクションが開始されている場合
				$this->rollback();//Roll back!!!
			}
			$this->getControl()->queryError(self::queryErrorWord,$queryWord);
			return false;
		}
		return true;
	}
	/**
	 * @see DBDriver::select()
	 */
	public function select($selectWord){
		//	print($selectWord);
		if(!($readData=mysql_query($selectWord))){
			$this->getControl()->queryError(self::selectErrorWord,$selectWord);
		}
		$readList=array();
		while($item = mysql_fetch_array($readData,MYSQL_ASSOC)){
			$readList[]=$item;
		}
		return $readList;
	}
	/**
	 * (non-PHPdoc)
	 * @see DBDriver::getSelectModel()
	 */
	public function getSelectModel($model,$column,$where=null,$orderBy=null,$limitStart=0,$limitCount=30){
		if($column===null){
			$columnClause="*";
			$find=false;
		}else{
			$columnClause=$column;
			$find=true;
		}
		$orderByClause="";
		if($orderBy!==null){
			$orderByClause=" order by ".$orderBy;
		}
		$selectWord="select ".$columnClause." from ".$model->getTable().$this->generateWhereClause($where).$orderByClause." limit ".$limitStart.",".$limitCount;
		return $this->fetchModel($selectWord,$model);
	}
	/**
	 * (non-PHPdoc)
	 */
	public function fetchModel($selectWord,$model){
	//	print($selectWord);
		if(!($readData=mysql_query($selectWord))){
			$this->getControl()->queryError(self::selectErrorWord,$selectWord);
			exit($selectWord);
			return null;
		}
		$modelList=array();
		while($item = mysql_fetch_array($readData,MYSQL_ASSOC)){
			$modelList[]=$model->createModel($item);
		}
		return $modelList;
	}
	/**
	 * @param string $where
	 */
	public function generateWhereClause($where){
		$whereClause="";
		if($where!==null){
			$whereClause=" where ".$where;
		}
		return $whereClause;
	}

	/**
	 * @param ModelRunnable $model
	 * @param String[] $valuesList
	 * @param String[] $columnList
	 * @return boolean
	 */
	public function insert($model,$valuesList,$columnList=null){
		$columnClause="";
		if($columnList!==null){
			$columnClause="(".implode(",",$columnList).")";
		}
		$valuesClause=implode(",",$valuesList);
		return $this->query("insert into ".$model->getTable()." ".$columnClause
				." values(".$valuesClause.")");
	}
	/**
	 * @param ModelRunnable $model
	 * @param String[] $updateList
	 * @param String $where
	 * @return boolean
	 */
	public function update($model,$updateList,$where=null){
		//	print_r($updateList);
		$updateClause=implode(",", $updateList);
		//		print($updateClause."<br/>");
		return $this->query("update ".$model->getTable().
				" set ".$updateClause.$this->generateWhereClause($where));
	}
	/**
	 * @param ModelRunnable $model
	 * @param String $where
	 * @return boolean
	 */
	public function delete($model,$where=null){
		return $this->query("delete from ".$model->getTable().$this->generateWhereClause($where));
	}
	public function setup(){//Set to queryable state.問い合わせ可能な状態にセット
		return mysql_select_db($this->getParameter()->getDbName(),$this->connect);
	}
	/**
	 * @see DBDriver::getLastInsertId()
	 */
	public function getLastInsertId($model){
		$data=$this->select("select last_insert_id() as id from ".$model->getTable());
		return intval($data[0]["id"]);
	}

}

?>