<?php
/**
 * Skeleton for model.
 * モデルの雛形となるクラス
 */
abstract class Model implements ModelRunnable{

	/**
	 * list for storing the data.
	 * データを格納するリスト
	 * @var string[]
	 */
	private $data;

	/**
	 * List to be stored error output.
	 * エラー出力を格納するリスト
	 * @var string[]
	 */
	private static $errorMessageList=array();
	/**
	 * List to be stored error item.
	 * エラー項目を格納するリスト
	 * @var string[]
	 */
	private static $errorItemList=array();//Error item is stored.


	/**
	 * @see ModelRunnable
	 */
	public static function createModel($data=null){
		$list=static::getColumnArray();
		$class=get_called_class();
		$model=new $class();
		if($data!==null){
			foreach ($list as $key => $value){
				call_user_func_array(array($model,"set"),array($key,$data[$value[self::valueIndex]]));
			}
		}else{
			if(($column=static::getColumn())===null){
				$find=CommonResources::nullCharacter;
				foreach ($list as $key => $value){
					call_user_func_array(array($model,"set"),array($key,$value[self::valueIndex]));
					if(isset($value[self::findIndex])&&$value[self::findIndex]){
						$find.=CommonResources::comma.static::getAs().$value[self::valueIndex];
					}
				}

				if($find===CommonResources::nullCharacter){
					call_user_func_array(array($model,"set"),array(self::findColumnKey,null));
				}else{
					call_user_func_array(array($model,"set"),array(self::findColumnKey,substr($find,strlen(CommonResources::comma))));
				}
				static::setColumn($model);
			}else{
				$model=$column;
			}
		}
		return $model;
	}
	/**
	 * @see SlModelRunnable
	 */
	public static function getAs(){
		return CommonResources::nullCharacter;
	}


	/**
	 * get table name.
	 * @return string
	 */
	public static function getTable(){
		return lcfirst(get_called_class());
	}

	/**
	 * @param string $name
	 * @return string
	 */
	public function get($name){
		return $this->data[$name];
	}
	/**
	 * @param string $name
	 * @param string $value
	 */
	public function set($name,$value){
		$this->data[$name]=$value;
	}

	/**
	 * @see ModelRunnable
	 */
	public static function find($db,$where=null,$orderBy=null,$limitStart=0,$limitCount=30){
		$columnModel=static::createModel();
		return $db->getSelectModel($columnModel,$columnModel->get(self::findColumnKey),$where,$orderBy,$limitStart,$limitCount);
	}
	/**
	 * @see ModelRunnable
	 */
	public static function find_column($db,$findColumn,$where=null,$orderBy=null,$limitStart=0,$limitCount=30){
		$columnModel=static::createModel();
		return $db->getSelectModel($columnModel,$findColumn,$where,$orderBy,$limitStart,$limitCount);
	}
	/**
	 * @see ModelRunnable
	 */
	public static function findBy($db,$whereColumn,$val,$addWhere=null,$all=false){
		$columnModel=static::createModel();
		if($addWhere===null){
			$addWhere=CommonResources::nullCharacter;
		}
		$column=null;
		if(!$all){
			$column=$columnModel->get(self::findColumnKey);
		}
		return $db->getSelectModel($columnModel,$column,$columnModel->get($whereColumn).CommonResources::equal.CommonResources::quote.$val.CommonResources::quote.$addWhere);
	}
	/**
	 * @see ModelRunnable
	 */
	public static function findByRand($db,$as,$id,$subTable,$where,$all=false,$limitStart=0,$limitCount=30,$add=""){
		$columnModel=static::createModel();
		$column=null;
		if(!$all){
			$column=$columnModel->get(self::findColumnKey);
			$all=true;
		}
		return $db->fetchModel("select ".$column." from ".$columnModel->getTable().",(select ".$as.$id." from ".$subTable.
				$where." order by rand() limit ".$limitStart.",".$limitCount.") as randam where ".$as.$id."=randam.".$id.$add, $columnModel);
	}
	/**
	 * @see ModelRunnable
	 */
	public static function findByCount($db,$where=null,$countColumn=null){
		$columnModel=static::createModel();
		if($countColumn===null){
			$countColumn=CommonResources::asterisk;
		}
		$list=$db->select("select count(".$countColumn.") as modelCount from ".$columnModel->getTable().$db->generateWhereClause($where));
		return $list[0]["modelCount"];
	}
	/**
	 * @see ModelRunnable
	 */
	public static function insert($db,$model){
		$list=static::getColumnArray();
		foreach ($list as $key => $value){
			$keyList[]=$key;
			if(($val=call_user_func_array(array($model,"get"),array($key)))===null){
				$valuesList[]=SqlSyntax::nullStr;
			}else{
				$valuesList[]=CommonResources::quote.$val.CommonResources::quote;
			}
		}
		$db->insert($model, $valuesList,$keyList);
	}
	/**
	 * @see ModelRunnable
	 */
	public static function save($db,$model,$all=false){
		$list=static::getColumnArray();
		$updateList=array();
		foreach ($list as $key => $value){
			if(!$all&&isset($value[self::updateIndex])&&!$value[self::updateIndex]){
			}else{
				if(($val=call_user_func_array(array($model,"get"),array($key)))===null){
					$updateList[]=$value[self::valueIndex].CommonResources::equal.SqlSyntax::nullStr;
				}else{
					$updateList[]=$value[self::valueIndex].CommonResources::equal.CommonResources::quote.$val.CommonResources::quote;
				}
			}
		}
		$db->update($model, $updateList,$list[self::id][self::valueIndex].CommonResources::equal.CommonResources::quote.$model->get(self::id).CommonResources::quote);
	}
	/**
	 * @see ModelRunnable
	 */
	public static function delete($db,$model){
		$list=static::getColumnArray();
		$db->delete($model,$list[self::id][self::valueIndex].CommonResources::equal.CommonResources::quote.$model->get(self::id).CommonResources::quote);
	}

	/**
	 * Methods that are related to action.
	 * パラメーターに関するメソッド
	 */
	/**
	 * @see ModelRunnable::formCheck
	 * @return ModelRunnable
	 */
	public static function formCheck(){
		$list=static::getColumnArray();
		$list[self::security]=static::getSecurityParam();
		$class=get_called_class();
		$model=new $class();
		foreach ($list as $key => $value){
			static::setParam($value, $key,$model );
		}
		return $model;
	}
		/**
		 * セキュリティー用のパラメーターを取得
		 */
		protected static function getSecurityParam(){
			return array(self::valueIndex=>self::security,self::typeIndex=>self::validation_security,self::requireIndex=>true);
		}

		/**
		 * Validation of parameter and set it.
		 * パラメーターをバリデーションしてセットする。
		 * @param array $param Parameter to be checked.チェック対象のパラメーター
		 * @param string $name
		 * @param ModelRunnable $model
		 * @return  void
		 */
		protected static function setParam($param,$name,$model){
			if(($value=static::getFormParam($name))===NULL||
					mb_strlen($value,AppConfigRunnable::character)<1){//Form has not been sent.
				if(isset($param[self::requireIndex])&&$param[self::requireIndex]){//If the parameter is required input.
					static::addErrorItemList($name);
					static::addErrorMessageList(CommonResources::requireErrorMessage, $param);
				}
				$model->set($name,CommonResources::nullCharacter);
			}else{
				static::runValidation($name,$param, $value);
				$model->set($name, $value);
			}
		}
		/**
		 * バリデーションを実行
	 	* @param array $param Parameter to be checked.チェック対象のパラメーター
	 	* @param string type $value バリデーションする値
	 	* @return void
	 	*/
		protected static function runValidation($name,$param,$value){
			if(isset($param[self::typeIndex])){
				if(($error=call_user_func_array(array("static",self::validation.$param[self::typeIndex]),array($value,$param)))!==null){
					static::addErrorItemList($name);
					static::addErrorMessageList($error, $param);
				}
			}else{
				if(($error=static::isValueLen($value, $param))!==null){
					static::addErrorItemList($name);
					static::addErrorMessageList($error, $param);
				}
			}
		}
		
		/**
		 * @see ModelRunnable
		 */
		public static function isValidation(){
			if(count(($list=static::getErrorItemList()))>0){//Occurrence of error.エラー発生
				if(static::isFirstAccess()){//first access.最初のアクセス
					static::resetError();
				}
			}else{
				return true;
			}
			return false;
		}

	/**
	 * Validation method.
	 */
		/**
		 *  Validation for numerical form.
		 * 数値形式のバリデーション
		 * @param string $value
		 * @return NULL|string Corresponding error message.
		 */
		public static function isValidation_numeric($value,$param){
			if(!is_numeric($value)){
				return CommonResources::validationErrorNumeric;
			}
			return static::isNumber($value, $param);
		}
		/**
		 * Validation for integer form
		 * 整数形式のバリデーション
		 * @param string $value
		 * @return NULL|string Corresponding error message.
		 */
		public static function isValidation_integer($value,$param){
			if(strval($value)!==strval(intval($value))){
				return CommonResources::validationErrorInteger;
			}
			return static::isNumber($value, $param);
		}
		/**
		 * Validation for Alphanumeric character
		 * 英数字のバリデーション
		 * @param string $value
		 * @return NULL|string Corresponding error message.
		 */
		public static function isValidation_ctypeAlnum($value,$param){
			if(ctype_alnum($value)){
				return static::isValueLen($value, $param);
			}
			return CommonResources::validationErrorCtypeAlnum;
		}
		/**
		 * Validation for alnum character.
		 * 半角英字のバリデーション
		 * @param string $value
		 * @return NULL|string Corresponding error message.
		 */
		public static function isValidation_alnum($value,$param){
			if(HtmlHelper::isAlnum($value)){
				return static::isValueLen($value, $param);
			}
			return CommonResources::validationErrorAlnum;
		}
		/**
		 *Validation for alphanumeric and hyphen and underbar character.
		 *半角英数字、ハイフン、アンダーバーのバリデーション
		 * @param string $value
		 * @return NULL|string Corresponding error message.
		 */
		public static function isValidation_ctypeAlnum_bar($value,$param){
			if(preg_match('/^[0-9a-zA-Z_-]+$/', $value)){
				return static::isValueLen($value, $param);
			}
			return CommonResources::validationErrorCtypeAlnum_bar;
		}
		/**
		 * Validation for mail address.
		 * メールアドレスのバリデーション
		 * @param string $value
		 * @return NULL|string Corresponding error message.
		 */
		public static function isValidation_mailAdd($value,$param){
			if(HtmlHelper::isCheck_mail($value)){
				return static::isValueLen($value, $param);
			}
			return CommonResources::validationErrorMailAdd;
		}
		/**
		 * Validation for url.
		 * @param string $value
		 * @return NULL|string Corresponding error message.
		 */
		public static function isValidation_url($value,$param){
			if(HtmlHelper::isUrl($value)){
				return static::isValueLen($value, $param);
			}
			return CommonResources::validationErrorUrl;
		}
		/**
	 	* Validation for true
	 	* @param string $value
	 	*/
		public static function isValidation_equals($value,$param){
			if($value===$param[self::equalsIndex]){
				return null;
			}
			return CommonResources::validationEquals;
		}
		public static function isValidation_security($value,$param){
			if(($securityValue=HtmlHelper::getSessionParam(static::getSecurityKeyName().self::sessionSecurity_value))!==NULL
					&&($time=HtmlHelper::getSessionParam(static::getSecurityKeyName().self::sessionSecurity_time))!==NULL){
				if($value===$securityValue&&$time>strtotime("now")){
					return null;
				}
			}
			return CommonResources::securityErrorMessage;
		}
		
		
		
		/**
		 * Validation on the size of the number.
		 * 数値の大きさのバリデーション
		 * @param string $value
		 * @param array $param
		 * @return NULL|string Corresponding error message.
		 */
		public static function isNumber($value,$param){
			if(isset($param[self::numMinIndex])&&$value<$param[self::numMinIndex]){
				return ErrorMessage::getCheckNumMinError($param[self::numMinIndex]);
			}else if(isset($param[self::numMaxIndex])&&$value>$param[self::numMaxIndex]){
				return ErrorMessage::getCheckNumMaxError($param[self::numMaxIndex]);
			}
			return null;
		}
		/**
		 * Validation on the length of the string.
		 * 文字の長さに関するバリデーション
		 * @param string $value
		 * @param array $param
		 * @return NULL|string Corresponding error message.
		 */
		public static function isValueLen($value,$param){
			if(isset($param[self::numMinIndex])&&mb_strlen($value,AppConfigRunnable::character)<$param[self::numMinIndex]){
				return ErrorMessage::getCheckMinLength($param[self::numMinIndex]);
			}else if(isset($param[self::numMaxIndex])&&mb_strlen($value,AppConfigRunnable::character)>$param[self::numMaxIndex]){
				return ErrorMessage::getCheckMaxLength($param[self::numMaxIndex]);
			}
			return null;
		}
		/**
		 * Get parameter from the form.
	 	* フォームからパラメーターを取得
	 	* @param string $name form name.
	 	* @return string
		 */
		protected static function getFormParam($name){
			return HtmlHelper::getPostParam(self::parseFormName($name));
		}
		/**
		 * @see ModelRunnable
		 */
		public static function parseFormName($key){
			return  strtolower(get_called_class()).CommonResources::underscore.$key;
		}
		
		/**
	 	* 最初のアクセスか
	 	* @return boolean
	 	*/
		protected static function isFirstAccess(){
			return !static::isErrorItem(self::security);
		}
		/**
		 * Name of the security key for the double update prevention.
		 * 二重更新防止のためのセキュリティーキーの名前
		 * @return string
		 */
		protected static function getSecurityKeyName(){
			return "securityKey";
		}

	/**
	 * セキュリティーをセットアップ
	 * @param string $time Effective time.有効時間
	 * @param ModelRunnable $model
	 * @return void
	 */
	public static function setupSecurity($time,$model){
		$value=substr((md5(date("YmdD His"))),0,10);
		HtmlHelper::setSessionParam(static::getSecurityKeyName().self::sessionSecurity_value, $value);
		HtmlHelper::setSessionParam(static::getSecurityKeyName().self::sessionSecurity_time,strtotime($time));
		$model->set(self::security, $value);
	}


	/**
	 * @see ModelRunnable
	 */
	public static function addErrorMessageList($message,$param) {
		if(isset($param[self::outputIndex])){//If the name of the output is set
			self::$errorMessageList[]=$param[self::outputIndex].$message;
		}
	}
	/**
	 * @see ModelRunnable
	 */
	public static function addErrorItemList($item) {
		self::$errorItemList[$item]=$item;
	}
	/**
	 * @see ModelRunnable
	 */
	public static function putErrorMessage($message){
		self::$errorMessageList[]=$message;
	}
	/**
	 * @see ModelRunnable
	 */
	public static function getErrorItemList(){
		return self::$errorItemList;
	}
	/**
	 * @see ModelRunnable
	 */
	public static function isErrorItem($item){
		return !isset(self::$errorItemList[$item]);
	}
	/**
	 * @see ModelRunnable
	 */
	public static function resetError(){
		self::$errorMessageList=array();
	}
	public static function resetErrorItem(){
		self::$errorItemList=array();
	}
	/**
	 * @see ModelRunnable
	 */
	public static function getErrorMessage(){
		return implode(self::$errorMessageList,"<br/>");
	}

}

?>