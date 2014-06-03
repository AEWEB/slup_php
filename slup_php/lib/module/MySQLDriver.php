<?php
/**
 * Class for MySql driver
 */
class MySQLDriver extends ModuleBase implements DBDriver{
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
	const selectErrorWord = "Failed to select::";
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
	 * sql
	 */
	const whereSyntax="where";
	const andSql="and";
	const orSql="or";
	const nullStr="null";
	const funcNow="now()";

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
		$this->log("----START TRANSACTION---");
		$this->transactionFlag=true;
	}
	public function commit(){
		mysql_query("commit",$this->connect);
		$this->log("----Commit---");
		$this->transactionFlag=false;
	}
	public function rollback(){
		mysql_query("rollback",$this->connect);
		$this->log("----rollback----");
		$this->transactionFlag=false;
	}
	public function query($queryWord){
		$this->log($queryWord);
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
		$this->log($selectWord);
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
	 * @see DBDriver::constructWhere()
	 */
	public function constructWhere($model,$options=null){
		$list=$model::getColumnArray();
		$columnModel=$model->createModel();
		$syntax=null;
		foreach ($list as $key => $val){
			if(($value=$model->get($key))!==null){
				$syntax=$this->addConstractWhere($syntax,$this->constructWhere_usual($columnModel, $key, $value),self::andSql);
			}
		}
		if(isset($options[DBDriver::queryOptionIndex_condition])){
			$list=$options[DBDriver::queryOptionIndex_condition];
			foreach ($list as $key => $val){
				if(is_numeric($key)){//key is numeric.
					$syntax=$this->addConstractWhere($syntax, $this->constructWhere_numeric($key, $val[DBDriver::queryOptionIndex_val]),$val[DBDriver::queryOptionIndex_logic]);
				}
			}
		}
		return $syntax===null ? CommonResources::nullCharacter : $syntax;
	}
		/**
		 * @param string $syntax
		 * @param string $value
		 * @param string $logic
		 * @return string
		 */
		protected function addConstractWhere($syntax,$value,$logic=null){
			if($syntax===null){
				$syntax=CommonResources::space.self::whereSyntax.CommonResources::space;
			}else if($logic!==null){
				$syntax.=CommonResources::space.$logic.CommonResources::space;
			}
			return $syntax.$value;
		}
		
		
		/**
		 * @param string $key
		 * @param string $value
		 * @return String
		 */
		protected function constructWhere_numeric($key,$value){
			return $value;
		}
		/**
		 * @param ModelRunnable $model
		 * @param string $key
		 * @param string $value
		 * @return string
		 */
		protected function constructWhere_usual($model,$key,$value){
			return $model->get($key).CommonResources::equal.CommonResources::quote.$value.CommonResources::quote;
		}
		
		
	/**
	 * @see DBDriver::constructOrder()
	 */
	public function constructOrder($model,$options=null){
		if(isset($options[DBDriver::queryOptionIndex_order])){
			$columnModel=$model->createModel();
			for($i=0;$i<count($options[DBDriver::queryOptionIndex_order]);$i++){
				$list[]=$columnModel->get($options[DBDriver::queryOptionIndex_order][$i][DBDriver::queryOptionIndex_order_column]).
					CommonResources::space.(isset($options[DBDriver::queryOptionIndex_order][$i][DBDriver::queryOptionIndex_order_value])?$options[DBDriver::queryOptionIndex_order][$i][DBDriver::queryOptionIndex_order_value]:DBDriver::asc);
			}
			return " order by ".implode(",",$list);
		}
		return CommonResources::nullCharacter;
	}
	/**
	 * @see DBDriver::constructProjection()
	 */
	public function constructProjection($model,$options=null){
		if(isset($options[DBDriver::queryOptionIndex_projection])){
			$columnModel=$model->createModel();
			for($i=0;$i<count($options[DBDriver::queryOptionIndex_projection]);$i++){
				$list[]=$columnModel->get($options[DBDriver::queryOptionIndex_projection][$i]);
			}
			return implode(",", $list);
		}
		return CommonResources::asterisk;
	}
		
	/**
	 * @see DBDriver::constructLimit()
	 */
	public function constructLimit($model,$options=null){
		if(isset($options[DBDriver::queryOptionIndex_limitStart])&&isset($options[DBDriver::queryOptionIndex_limitCount])){
			return " limit ".$options[DBDriver::queryOptionIndex_limitStart].",".$options[DBDriver::queryOptionIndex_limitCount];
		}
		return CommonResources::nullCharacter;
	}
	
	/**
	 * @see DBDriver::getSelectModel()
	 */
	public function getSelectModel($model,$options=null){
		return $this->fetchModel("select ".$this->constructProjection($model,$options)." from ".$model->getTable().
			$this->constructWhere($model,$options).$this->constructOrder($model,$options).$this->constructLimit($model,$options),$model);
	}
	/**
	 * @see DBDriver::fetchModel
	 */
	public function fetchModel($selectWord,$model){
		$this->log($selectWord);
		if(!($readData=mysql_query($selectWord))){
			$this->getControl()->queryError(self::selectErrorWord,$selectWord);
			return null;
		}
		$modelList=array();
		while($item = mysql_fetch_array($readData,MYSQL_ASSOC)){
			$modelList[]=$model->createModel($item);
		}
		return $modelList;
	}
	
	/**
	 * @see DBDriver::insert()
	 */
	public function insert($model,$options=null){
		$list=$model::getColumnArray();
		$columnModel=$model->createModel();
		$columnList=array();
		$valuesList=array();
		foreach ($list as $key => $val){
			$columnList[]=$columnModel->get($key);
			if(($value=$model->get($key))!==null){
				$valuesList[]=CommonResources::quote.$model->get($key).CommonResources::quote;				
			}else{
				$valuesList[]=self::nullStr;
			}
		}
		return count($columnList)<1 ? false:$this->query("insert into ".$model->getTable().CommonResources::space.CommonResources::leftBrackets.
			implode(",", $columnList).CommonResources::rightBrackets.CommonResources::space."values".CommonResources::leftBrackets.
			implode(",", $valuesList).CommonResources::rightBrackets);
	}
	/**
	 * @see DBDriver::update()
	 */
	public function update($model,$options=null){
		$list=$model::getColumnArray();
		$columnModel=$model->createModel();
		$updateList=array();
		foreach ($list as $key => $val){
			if(isset($options[DBDriver::queryOptionIndex_update][$key])){
				$updateList[]=$columnModel->get($key).CommonResources::equal.CommonResources::quote.$options[DBDriver::queryOptionIndex_update][$key].CommonResources::quote;
			}else if(array_key_exists($key,$options[DBDriver::queryOptionIndex_update])){
				$updateList[]=$columnModel->get($key).CommonResources::equal.self::nullStr;
			}
		}
		return count($updateList)< 1? false : $this->query("update ".$model->getTable().
			" set ".implode(",",$updateList).$this->constructWhere($model,$options));
	}
	/**)
	 * @see DBDriver::delete()
	 */
	public function delete($model,$options=null){
		return $this->query("delete from ".$model->getTable().$this->constructWhere($model,$options));
	}
	public function setup(){//Set to queryable state.問い合わせ可能な状態にセット
		$this->log("---------setup---------- ".print_r($this->getParameter(),true)."------------------------");
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