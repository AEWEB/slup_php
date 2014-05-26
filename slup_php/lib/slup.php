<?php
/**
 * Redefinition of the Exception.
 * Handle the exception handling of all.
 * Exceptionの再定義。
 * 全ての例外処理を処理する。
 */
class MyException extends Exception{
	public function __construct($errno, $errstr, $errfile, $errline){
		$errlev = array(
				E_USER_ERROR   => 'FATAL',
				E_ERROR        => 'FATAL',
				E_USER_WARNING => 'WARNING',
				E_WARNING      => 'WARNING',
				E_USER_NOTICE  => 'NOTICE',
				E_NOTICE       => 'NOTICE',
				E_STRICT       => 'E_STRICT'
		);
		$add_msg= (string)$errno;
		if (isset($errlev[$errno])) {
			$add_msg = $errlev[$errno] . ' : ';
		}
		parent::__construct($add_msg . $errstr, $errno);
		$this->file = $errfile;
		$this->line = $errline;
	}
}
function errorHandler ($errno, $errstr, $errfile, $errline){
	throw new MyException($errno, $errstr, $errfile, $errline);
}
set_error_handler("errorHandler");//Set the error handler.エラーハンドラーをセット
/**
 * database parameter
 */
class DatabaseParameter{
	/**
	 * @var String
	 */
	private $serverName;
	/**
	 * @var String
	 */
	private $userName;
	/**
	 * @var String
	 */
	private $password;
	/**
	 * @var String
	 */
	private $dbName;
	/**
	 * @var String
	 */
	private $caracterCode;
	/**
	 * constructor
	 * @param String $serverName
	 * @param String $userName
	 * @param String $password
	 * @param String $dbName
	 * @param String $caracterCode
	 */
	public function DatabaseParameter(
			$serverName,$userName,$password,$dbName,$caracterCode){
		$this->serverName=$serverName;
		$this->userName=$userName;
		$this->password=$password;
		$this->dbName=$dbName;
		$this->caracterCode=$caracterCode;
	}
	/**
	 * @return String
	 */
	public function getServerName(){
		return $this->serverName;
	}
	/**
	 * @return String
	 */
	public function getUserName(){
		return $this->userName;
	}
	/**
	 * @return String
	 */
	public function getPassword(){
		return $this->password;
	}
	/**
	 * @return String
	 */
	public function getDbName(){
		return $this->dbName;
	}
	/**
	 * @return String
	 */
	public function getCaracterCode(){
		return $this->caracterCode;
	}
}

/**
 * Interface for connecting to the controller from DB.
 * DBからコントローラーに接続するためのインタフェース
 */
interface DBController{
	/**
	 * Notify query error.
	 * クエリーエラーを通知
	 * @param string $error
	 * @param string $query
	 * @return void
	 */
	public function queryError($error,$query);//
}
/**
 * Interface for DB connection.
 */
interface DBDriver{
	
	const queryOptionIndex_projection="projection";
	const queryOptionIndex_condition="condition";
	const queryOptionIndex_logic="logic";
	const queryOptionIndex_val="val";
	const queryOptionIndex_order="order";
	const queryOptionIndex_limitStart="limitStart";
	const queryOptionIndex_limitCount="limitCount";
	const queryOptionIndex_update="update";
	
	
	/**
	 * start transaction.
	 * トランザクションの開始
	 * @return void
	 */
	public function startTransaction();
	/**
	 * run commit.
	 * @return commit
	 */
	public function commit();
	/**
	 * run rollback
	 * @return void
	 */
	public function rollback();
	/**
	 * run query.
	 * @param String $queryWord
	 * @return void
	 */
	public function query($queryWord);
	/**
	 * run select
	 * @param String $selectWord
	 * @return string[]
	 */
	public function select($selectWord);
	/**
	 * construct where statement.
	 * where文を生成する
	 * @param ModelRunnable $model
	 * @param string[] $options
	 */
	public function constructWhere($model,$options=null);
	/**
	 * construct order by statement.
	 * order by 文を生成する
	 * @param ModelRunnable $model
	 * @param string[] $options
	 */
	public function constructOrder($model,$options=null);
	/**
	 * construct projection statement.
	 * 射影構文を生成
	 * @param ModelRunnable $model
	 * @param string[] $options
	 */
	public function constructProjection($model,$options=null);
	/**
	 * construct limit statement.
	 * limit文を生成
	 * @param ModelRunnable $model
	 * @param string[] $options
	 */
	public function constructLimit($model,$options=null);
	
	/**
	 * to select.
	 * 照会する
	 * @param ModelRunnable $model
	 * @param String[] $options
	 */
	public function getSelectModel($model,$options=null);
	/**
	 * @return ModelRunnable
	 * @param string $selectWord
	 * @param $model
	 */
	public function fetchModel($selectWord,$model);
	/**
	 * @param ModelRunnable $model
	 * @param String[] $options
	 * @return boolean
	 */
	public function insert($model,$options=null);
	/**
	 * @param ModelRunnable $model
 	* @param String $options
	 * @return boolean
	 */
	public function update($model,$options=null);
	/**
	 * @param ModelRunnable $model
 	* @param String $options
	 * @return boolean
	 */
	public function delete($model,$options=null);

	/**
	 * Set to queryable state.
	 * 問い合わせ可能な状態にセット
	 */
	public function setup();
	/**
	 * @return int
	 * @param ModelRunnable
	 */
	public function getLastInsertId($model);
	

}
/**
 * interface for skeleton model.
 */
interface ModelRunnable{
	/**
	 * @var String
	 */
	const valueIndex="value";
	const updateIndex="update";
	
	/**
	 * Store the flag of whether require.
	 * 必須項目かどうかのフラグを格納
	 */
	const requireIndex  = "require";
	/**
	 * Store the output name.
	 * 出力用の名前を格納
	 */
	const outputIndex = "outputName";
	/**
	 * Store the minimum number of characters or minimum value.
	 * 最小値もしくは最小字数を格納
	 */
	const numMinIndex= "numMin";
	/**
	 * Store the max number of characters or max value.
	 * 最小値もしくは最小字数を格納
	 */
	const numMaxIndex = "numMax";
	/**
	 * Stores the type of validation.
	 * バリデーションのタイプを格納
	 * @var String
	 */
	const typeIndex  = "type";
	/**
	 *  Stores the true value of validation.
	 * @var string
	 */
	const equalsIndex = "equals";
	
	
	/**
	 * index for storing method to run.
	 */
	const formIndex="form";
	/**
	 * index for storing form option.
	 */
	const formType="form_type";
	const formCols="form_cols";
	const formRows="form_rows";
	const formList="form_list";
	const formValue="form_value";
	const formIndexOption="form_option";
	
	/**
	 * Session index for security.
	 * セキュリティーのためのセッションインデックス
	* @var string
	 */
	/**
	 * Store the value.
	 * 値を格納
	 */
	const sessionSecurity_value="_value";
	/**
	 * Store the time.
	 * 時間を格納
	 */
	const sessionSecurity_time="_time";
	
	/**
	 * Index for form check list.
	 * フォームチェックのためのインデックス定義
	 * @param string
	 */
	/**
	 * Index for validation.
	 * バリデーションのためのインデックス定義
	 */
	const validation="isValidation_";
		/**
		 * Validation for numerical form
		 * 数値形式のバリデーション
		 */
		const validation_numeric="numeric";
		/**
		 * Validation for integer form
		 * 整数形式のバリデーション
		 */
		const validation_integer="integer";
		/**
		 * Validation for Alphanumeric character
		 * 英数字のバリデーション
		 */
		const validation_ctypeAlnum="ctypeAlnum";
		/**
		 *Validation for alnum character.
		 * 半角英字のバリデーション
		 */
		const validation_alnum="alnum";
		/**
		 *Validation for alphanumeric and hyphen and underbar character.
		 *半角英数字、ハイフン、アンダーバーのバリデーション
		 */
		const validation_ctypeAlnum_bar="ctypeAlnum_bar";
		/**
		 * Validation for mail address.
		 * メールアドレスのバリデーション
		 */
		const validation_mailAdd="mailAdd";
		/**
		 * Validation for url.
		 */
		const validation_url="url";
		/**
		 *  Validation for true.
		 */
		const validation_equals="equals";
		/**
		 *  Validation for security.
		 */
		const validation_security="security";
	
	
	/**
	 * データを格納するためのインデックス
	  * @var String
	 */
		const id="id";
		const security="security";	
	
	
	/**
	 * get table name.
	 * @return string
	 */
	public static function getTable();
	/**
	 * create model.
	 * @return ModelRunnable
	 * @param Object data
	 */
	public static function createModel($data=null);
	/**
	 * get array with the column.
	 * 列を配列にして返す
	 * @return string[][]
	 */
	public static function getColumnArray();
	/**
	 * @return ModelRunnable
	 */
	public static function getColumn();
	/**
	 * @param ModelRunnable $model
	 * @return void
	 */
	public static function setColumn($model);
	/**
	 * @param string $name
	 * @return string
	 */
	public function get($name);
	/**
	 * @param string $name
	 * @param string $value
	 */
	public function set($name,$value);
	
	
	/**
	 * @param DBDriver $db
	 * @param ModelRunnable $model
	 * @param String[] $options
	 */
	public static function find($db,$model,$options=null);
	
	/**
	 * @param DBDriver $db
	 * @param ModelRunnable $model
	 * @param String[] $options
	 * @param String $as
	 * @param String $id
	 * @param String $subTable
	 */
	public static function findByRand($db,$model,$options,$as,$id,$subTable);
	/**
	 * @param DBDriver $db
	 * @param ModelRunnable $model
	 * @param string[] $options
	 */
	public static function findByCount($db,$model,$options=null);
	/**
	 * @param DBDriver $db
	 * @param ModelRunnable $model
	 * @return boolean
	 */
	public static function insert($db,$model);
	/**
	 * @param DBDriver $db
	 * @param ModelRunnable $model
	 * @param boolean $all
	 * @return boolean
	 */
	public static function save($db,$model,$updateId=null,$all=false);
	/**
	 * @param DBDriver $db
	 * @param ModelRunnable $model
	 */
	public static function delete($db,$model);
	
	/**
	 * フォームチェック
	 */
		/** 
		* To check parameters sent to the form.
		* フォームに送信されたパラメータからモデルを作成する
		* @return ModelRunnable
		**/
		public static function formCheck();
		/**
		 * To check the form.
		 * バリデーションが正常にしているか
		 * @return boolean
		 */
		public static function isValidation();
		/**
		 * @param string $key
		 * @return string
		 */
		public static function parseFormName($key);
	
	/**
	 * エラーメッセージ関連
	 */
	/**
	 * add error message.
	 * エラーメッセージを追加
	 * @return void
	 * @param string $message エラーメッセージ
	 * @param array $param Parameter to be checked.チェック対象のパラメーター
	 */
	public static function addErrorMessageList($message,$param);
	/**
	 * Simply add the error message.
	 * エラーメッセージをそのまま追加する
	 * @param string $message
	 * [return void
	 */
	public static function putErrorMessage($message);
	/**
	 * add error item.
	 * エラー項目を追加
	 * @return void
	 * @param string $item　エラー項目
	 */
	public static function addErrorItemList($item) ;
	/**
	 * Get error item list.
	 * エラー項目のリストを取得
	 * @return string[] error item.エラー項目
	 */
	public static function getErrorItemList();
	/**
	 * 特定のパラメーターがエラーか
	 * @param string $item
	 * @return boolean
	 */
	public static function isErrorItem($item);
	/**
	 * Reset error state.
	 * エラー状態をリセット
	 * @return void
	 */
	public static function resetError();
	public static function resetErrorItem();
	
	/**
	 * @return string
	 */
	public static function getErrorMessage();
	
	/**
	 * セキュリティーをセットアップ
	 * @param string $time Effective time.有効時間
	 * @param ModelRunnable $model
	 * @return void
	 */
	public static function setupSecurity($time,$model);
	
}
interface ControllerRunnable extends DBController{
	/**
	 * DB index for basic.
	 * 基本となるDBインデックス
	 * @var int
	 */
	const basicDbIndex= 0;
	/**
	 * exit session.
	 * @return void
	 */
	public function exitSession();
	/**
	 * get database object.
	 * @param int $dbIndex
	 * @return DBDriver
	 */
	public function getDB($dbIndex);

	/**
	 * run process.
	 * @return string
	 */
	public function run() ;
	/**
	 * Get the index of the action.
	 * アクションのインデックスを取得
	 * @return string
	 */
	public function getActionIndex();
	/**
	 * Get the extension.
	 * 拡張子を取得。
	 * @return string
	 */
	public function getExetension();
	/**
	 * header for web page.
	 * ヘッダーファイルを取得
	 * @return string
	 */
	public function getHeader();
	/**
	 * footer for web page.
	 * フッダーファイルを取得
	 * @return string
	 */
	public function getFooter();
	/**
	 * エラーの処理
	 * Process for error.
	 * @param Exception $e
	 */
	public function errorOutput($e);
	/**
	 * get title for web page.
	 * @return string
	 */
	public function getTitle() ;
	/**
	 * Get file for output.
	 * @return string
	 */
	public function getOutput();
	/**
	 * @return string
	 */
	public function getAjaxOutput() ;
	/**
	 * Get path to view.
	 * ビューへのパスを取得
	 * @return string
	 */
	public function getViewPath();
	/**
	 * Get path to menu.
	 * メニューへのパスを取得
	 */
	public function getAppMenu();//
	 /**
	 * Get the form for the transition destination.
	 * 遷移先のフォームを取得
	 * @param $name
	 * @param @callMethod Method that will be called in action destination.アクション先で呼び出されるメソッド
	 * @param $method HTTP method
	 * @param $option other.
	 * @param $model ModelRunnable
	 * @param $time String
	 * @return string
	 */
	 public function getActionForm($name,$callMethod,$method,$option,$model,$time=AppConfingRunnable::securityTime);
	 /**
	 * action form for ajax.
	 * @param ModelRunnable $model
	 * @return string
	 */
	 public function getAjaxActionForm($model);
	 /**
	 * Get URL for applications in used JavaScript.
	 * Jsで使うアプリケーションURL
	 * @return string
	 */
	 public function getJsAppUrl();
	 /**
	 * Get Error URL for applications in used JavaScript.
	 * Jsで使うアプリケーションのエラーURL
	 * @return string
	 */
	 public function getJsErrorUrl();
	 /**
	 * Output JavaScript.
	 * JavaScriptを出力
	  * @return void
	  */
	  public function printJs();
	 /**
	 * Output Css.
	 * Cssを出力
	 * @return void
	 */
	 public function printCss();
	 /**
	 * @return Sl_user
	 */
	 public function getUser();
	  /**
	 * @return string
	 */
	 public function getAction();
	 /**
	  * Get file name of the transition destination.
	  * 遷移先のコントローラー名を取得
	  * @return string
	  */
	 public static function getControllerName() ;
	 
}
/**
 *Interface for auth access class.
 *認証アクセスクラスのためのインタフェース。
 */
interface AuthModel extends ModelRunnable{
	/**
	 * Run auth.
	 * 認証を実行
	 * @return AuthUser||null
	 */
	public static function runAuth() ;
	/**
	 * To check whether API can be use.
	 * apiが使えるか
	 * @return boolean
	 */
	public static function isApi() ;
	/**
	 * remove auth info.
	 * 認証情報を消去
	 * @return void
	 */
	public static function remove();
	
	/**
	 * @return Object
	 * @param string $url
	 * @param string $param
	 */
	public static function getUrl($url ,$param);
	/**
	 * @return string
	 */
	public static function getIdIndex();
	/**
	 * @return string
	 */
	public static function getCareerId();
	/**
	 * @return string
	 */
	public static function getSaveIndex();
}


require_once AppConfig::$config->getLibPath()."module.php";
?>