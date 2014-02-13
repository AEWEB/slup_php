<?php
abstract class Controller implements ControllerRunnable{
	

	/**
	 * @var DBDriver[]
	 */
	private $dbList;
	/**
	 * @var string
	 */
	private $action;
	/**
	 * @var String
	 */
	private $jsAppUrl;
	/**
	 * @var string
	 */
	private $title;
	/**
	 * @var Sl_user
	 */
	private $user=null;

	/**
	 * path to view.
	 * ビューへのパス
	 * @var string
	 */
	private $viewPath;
	/**
	 * @var string
	 */
	private $appMenu;

	/**
	 * action that run by default.
	 * デフォルトで実行されるアクション
	 * @var string
	 */
	const action_index="index";

	/**
	 * Get list of router configuration.
	 * ルーター設定のリストを取得
	 * @string[]
	 */
	abstract protected function getRootList();

	/**
	 * constructor
	 */
	public function Controller(){
		$this->init();
	}
	/**
	 * @see DBController
	 */
	public function queryError($error,$query){
		$this->errorOutput($error."\r\n".$query);
	}
	/**
	 * Initialization
	 */
	protected function init(){//
		AppConfig::$config->init();
		$this->createDBDriver();//Create DB Connection Class.DBへのコネクションクラスを生成
		$this->setupSessionStart();
		session_start();//Start session.セッションをスタート
		$this->setup();
	}
	/**
	 * Create db object.
	 *データベースオブジェクトを生成
	 *@return void
	 */
	protected function createDBDriver(){
		global $dbParameter_0;
		$this->setDB(ControllerRunnable::basicDbIndex,new MySQLDriver($dbParameter_0,$this));
	}
	/**
	 * Set for start the session.
	 * セッションをスタートさせるためのセット
	 * @return void
	 */
	protected function setupSessionStart(){
		if(AppConfig::isSsl()){
			$this->setupSsl();
		}else{
			$this->setupNotSsl();
		}
	}
	/**
	 * Set up for SSL access.
	 * SSLアクセスの場合のセットアップ
	 * @return void
	 */
	protected function setupSsl(){
		$this->setJsAppUrl(AppConfig::getSslHost());
		session_set_cookie_params(0,AppConfigRunnable::sslCookieParams,"");
	}
	/**
	 * Set up for not SSL access.
	 * SSLアクセスではない場合のセットアップ
	 * @return void
	 */
	protected function setupNotSsl(){
		$this->setJsAppUrl(AppConfig::getHost());
		session_set_cookie_params(0,AppConfigRunnable::cookieParams,"");
	}
	/**
	 * Setup system.
	 * セットアップする。
	 * @return void
	 */
	protected function setup(){
		$this->accessDistribution();
		$this->setupSessionData();
		$this->setAction($this->analysisAction());
		$this->setTitle(AppConfigRunnable::formalName);
		$this->setAppMenu($this->getViewPath().AppConfigRunnable::defaultMenuFile);
	}

	/**
	 * Access distribution.
	 * アクセスを振り分ける
	 * @return void
	 */
	protected function accessDistribution(){
		$this->viewPath=AppConfig::getViewPath();
		if(HtmlHelper::isSpAccess()){//access from smartphone
			$this->viewPath=$this->viewPath.AppConfigRunnable::spView;
		}
	}
	/**
	 * set session for user.
	 */
	protected function setupSessionData(){
		if(($userId=HtmlHelper::getSessionParam(AppConfigRunnable::userSessionIndex))===NULL
				||($finger=HtmlHelper::getSessionParam(AppConfigRunnable::fingerPrintIndex))===NULL){
		}else if(!$this->isFingerprint($finger)){//セッションが不正
		}else{
			if(count($user=Sl_user::findByIdSession($this->getAuthDB(),$userId))>0){//セッションデータが存在
				$this->setUser($user[0]);
				return;
			}
		}
		unset($_SESSION[AppConfigRunnable::userSessionIndex]);//セッション情報を破棄
		unset($_SESSION[AppConfigRunnable::fingerPrintIndex]);
		$this->setUser(null);
	}
	/**
	 * register session.
	 * セッションに登録
	 * @return boolean
	 */
	protected function registerSessionUser(){
		if(count($user=Sl_user::findByIdLogin($this->getAuthDB(),$this->getUser()->get(sl_user::id),$this->getUser()->get(sl_user::password)))>0){//データが存在する
			HtmlHelper::setSessionParam(AppConfigRunnable::userSessionIndex,$user[0]->get(Sl_user::id));
			HtmlHelper::setSessionParam(AppConfigRunnable::fingerPrintIndex,$this->getTrueFinger());
			return true;
		}
		return false;
	}
	/**
	 * Check the session.
	 * セッションを確認
	 * @param string $finger
	 * @return boolean
	 */
	protected function isFingerprint($finger){
		return $this->getTrueFinger()===$finger;
	}
	/**
	 * onvert the session information.
	 * セッション情報を変換
	 */
	protected function getTrueFinger(){
		$str=AppConfigRunnable::fingerprint;
		if ( ! empty( $_SERVER['HTTP_USER_AGENT'])){
			$str .= $_SERVER['HTTP_USER_AGENT'];
		}
		if ( ! empty( $_SERVER['HTTP_ACCEPT_CHARSET'])){
			$str .= $_SERVER['HTTP_ACCEPT_CHARSET'];
		}
		$str .= session_id();
		return md5( $str );
	}

	/**
	 * Get file name of the transition destination.
	 * 遷移先のファイル名を取得
	 * @return string
	 */
	public static function getControllerName() {
		return HtmlHelper::getEscapeParam($_GET[AppConfigRunnable::appAccessIndex]);
	}


	/**
	 * Analyze the action for execution.
	 * 実行するアクションを解析。
	 * @return Ambigous <string,string>
	 */
	protected function analysisAction(){//
		$action=$this->getActionParam();
		if($action!==null){
			AppConfig::$appHomeFromBrowserPath="../";
		}
		$list=$this->getRootList();
		return isset($list[$action]) ? $list[$action]:self::action_index;
	}
	/**
	 * @see ControlllerRunnable::run()
	 */
	public function run(){
		return $this->getViewPath().$this->getControllerName().CommonResources::slash.call_user_func_array(array($this,$this->getAction()),array()).".php";
	}
	/**
	 * action that is run by default
	 * デフォルトで実行されるアクション
	 * @return string
	 */
	public function index(){
		return Controller::action_index;
	}
	/**
	 * @see ControlllerRunnable::exitSession()
	 */
	public function exitSession(){
		$_SESSION = array();
		try{
			if (isset($_COOKIE[session_name()])) {
				setcookie(session_name(), '', time()-42000, '/');
			}
			session_destroy();
		}catch (Exception $e){
		}
	}
	
	/**
	 * エラーの処理
	 * Process for error.
	 * @param Exception $e
	 */
	public function errorOutput($e){
		mail(AppConfigRunnable::systemMailAdd, "システムエラー",$e,
				"From: ".AppConfigRunnable::systemMailAdd);
		require_once $this->getViewPath()."error.php";
		print($e);
		$this->exitSession();
		exit();
	}





	/**
	 * @see ControllerRunnable
	 */
	public function getAction(){
		return $this->action;
	}
	/**
	 * @param string $action
	 */
	protected function setAction($action) {
		$this->action=$action;
	}
	/**
	 * Get the parameters of the action.
	 * アクションのパラメーターを取得
	 * @return Ambigous <NULL, string>
	 */
	protected function getActionParam() {//
		return HtmlHelper::getGetParam($this->getActionIndex());
	}
	/**
	 * @see ControlllerRunnable::getActionIndex
	 */
	public function getActionIndex(){
		return AppConfigRunnable::actionAccessIndex;
	}

	/**
	 * @see ControllerRunnable::getUser()
	 */
	public function getUser(){
		return $this->user;
	}
	protected function setUser($user){
		$this->user=$user;
	}

	/**
	 * url for app.
	 * @return string
	 */
	protected function getAppUrl(){
		return AppConfig::getHost();
	}
	/**
	 * @see ControllerRunnable::printJs()
	 */
	public function printJs(){
	}
	/**
	 * @see ControllerRunnable::printCss()
	 */
	public function printCss(){}
	
	/**
	 * @see ControllerRunnable::getJsAppUrl()
	 */
	public function getJsAppUrl(){
		return $this->jsAppUrl;
	}
	/**
	 * Set URL for applications in used JavaScript.
	 * Jsで使うアプリケーションURLをセット
	 * @return string
	 */
	protected function setJsAppUrl($jsAppUrl){
		$this->jsAppUrl=$jsAppUrl;
	}
	/**
	 * @see ControllerRunnable::getJsErrorUrl()
	 */
	public function getJsErrorUrl(){
		return $this->getAppUrl();
	}
	/**
	 * @see ControllerRunnable::getActionForm()
	 */
	public function getActionForm($name,$callMethod,$method,$option,$model){
		$url=HtmlHelper::getActionUrl(static::getControllerName(), $callMethod);
		$tag=HtmlHelper::form($name, $url, $method, $option);
		return $tag.HtmlHelper::input("hidden",$model::parseFormName(ModelRunnable::security),$model->get(ModelRunnable::security),"");
	}
	/**
	 * @see ControllerRunnable::getAjaxActionForm()
	 */
	public function getAjaxActionForm($model){
		return HtmlHelper::input("hidden",$model::parseFormName(ModelRunnable::security),$model->get(ModelRunnable::security),"id='".$model::parseFormName(ModelRunnable::security)."'");
	}
	
	/**
	 * @see ControlllerRunnable::getExetension()
	 */
	public function getExetension() {
		return CommonResources::nullCharacter;
	}
	/**
	 * @see ControlllerRunnable::getHeader()
	 */
	public function getHeader(){//header for web page.
		return $this->getViewPath()."header.php";
	}
	/**
	 * @see ControlllerRunnable::getFooter()
	 */
	public function getFooter(){//footer for web page.
		return $this->getViewPath()."footer.php";
	}
	/**
	 * @see ControlllerRunnable::getOutput()
	 */
	public function getOutput(){//Get file for output.
		return $this->getViewPath()."view.php";
	}
	/**
	 * @see ControlllerRunnable::getAjaxOutput()
	 */
	public function getAjaxOutput() {
		return $this->getViewPath()."ajax.php";
	}
	protected function printAjaxError() {
		$this->printAjax("error");
	}
	protected function printAjaxFalse(){
		$this->printAjax("false");
	}
	protected function printAjax($str){
		print($str);
		exit();
	}
	
	
	/**
	 * @see ControlllerRunnable::getTitle()
	 */
	public function getTitle() {
		return $this->title;
	}
	/**
	 * @param string $title
	 */
	protected function setTitle($title){//Set title for web page.
		$this->title=$title;
	}
	/**
	 * @see ControlllerRunnable::getViewPath()
	 */
	public function getViewPath(){//Get path to view.ビューへのパスを取得
		return $this->viewPath;
	}
	/**
	 * @see ControlllerRunnable::getAppMenu()
	 */
	public function getAppMenu(){//Get path to menu.メニューへのパスを取得
		return $this->appMenu;
	}
	protected function setAppMenu($appMenu) {
		$this->appMenu=$appMenu;
	}

	/**
	 * @see ControlllerRunnable::getDB()
	 */
	public function getDB($dbIndex){
		return $this->dbList[$dbIndex];
	}
	/**
	 * get db for auth.
	 * @return DBDriver
	 */
	protected function getAuthDB(){
		return $this->dbList[self::basicDbIndex];
	}
	/**
	 * get database object.
	 * データベースオブジェクトをセット
	 * @param int $dbIndex
	 * @param DBDriver $db
	 */
	protected function setDB($dbIndex,$db) {
		$this->dbList[$dbIndex]=$db;
	}

}
?>