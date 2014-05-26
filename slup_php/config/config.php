<?php
	
	interface AppConfigRunnable{
		
		/**
		 * Character code in use for the application.
		 * アプリケーションで使用する文字コード
		 * @var string
		 */
		const character="UTF-8";
		/**
		 * Formal name for application.
		 * アプリケーションの正式名称
		 */
		const formalName="SLup!";
		/**
		 *mail address for system.
		 * @var string
		 */
		const systemMailAdd="sohara_contact9022@jcom.home.ne.jp";
		/**
		 * url for redurect.
		 */
		const redirectUrl="top";
		
		/**
		 * Security time.
		 */
			const securityTime="5 minute";
		
		/**
		 * auth parameter.
		 */
			/**
			 * index for user session.
			 */
			const userSessionIndex="slUser_index";
			/**
			 * security key.
			 */
			const fingerprint="slupFinger";
			/**
			 * index for session variable.
			 */
			const fingerPrintIndex="slUser_finger";
			/**
			 * Restriction value for login.
			 */
			const restrictionLoginValue="5";
			/**
			 * usual registered career id.
			 * 通常の登録キャリア
			 * var string
			 */
			const usualCareer_id="0";
			/**
			 * usual restriction value.
			 * 通常の制限値
			 * @var　string
			 */
			const usualRestriction  = "0";
			
			
		/**
		 * auth config.
		 */
			/**
			 * twitter config .
			 */
			const twitter_consumerKey="GVu1j5SqVqsBBQfbDHiu1Q";
			const twitter_consumerSecret="F6cEi6xWFWBoz2KJmYVRzdueiJmvKXLZAC21HAb3M";
			const twitter_callbackUrl="https://localhost/top/twitterAuth";
			const twitter_idIndex="tw/";
			const twitter_careerId="1";
			const twitter_saveIndex="twitterSave";
			/**
			 * facebook config .
			 */
			const facebook_appId="163776520487178";
			const facebook_secret="3a27a6480e2a729287598c10ed39bd40";
			const facebook_idIndex="fb/";
			const facebook_careerId="2";
			const facebook_saveIndex="facebookSave";
			
		
		/**
		 * path
		 * @return string
		 */
			/**
			 * Directory name for the library.
			 * ライブラリのディレクトリ名
			 * @var string
			 */
			public static function getLibPath();
			/**
			 * Directory name for the application.
			 * アプリケーションのディレクトリ名
			 * @var string
			 */
			public static function getAppPath();
			/**
			 * Directory name for the resource.
			 * リソースのディレクトリ名
			 * @var string
			 */
			public static function getResourcePath();
			/**
			 * Directory name for the config.
			 * @var string
			 */
			public static function getConfigPath();
			/**
			 * view path.
			 * @var string
			 */
			public static function getViewPath();
			public static function getModelPath();
			public static function getResourcePathFromBrowser();
			public static function getImagePath();
			public static function getExtLibPath();
			public static function getImageSavePath();
			public static function getLogPath();
			
			/**
			 * Get host.
			 * ホストを取得
			 * @return string
			 */
			public static function getHost();
			/**
			 * Get ssl host.
			 * sslのホストを取得
			 * @return
			 */
			public static function getSslHost();
			/**
			 * Check ssl access.
			 * SSL接続か調べる
			 * @return boolean true=>ssl
			 */
			public static function isSsl();
			
			
			/**
			 * View path to sp.
			 * スマートフォンへのビューパス
			 * @var string
			 */
			const spView  = "sp/";
			
			/**
			 * index for access app.
			 * @var string
			 */
			const appAccessIndex="app";
			/**
			 * index for access action.
			 * @var string
			 */
			const actionAccessIndex="action";
	
		/**
		 * file
		 */
			/**
			 * Avatar for default
			 * デフォルトのアバター
			 * @var string
			 */
			const defaultImage="default.png";
			/**
			 * menu file for default.
			 * @var string
			 */
			const defaultMenuFile="menu.php";
			const imageMaxSize="1000";
			const imageUploadPath="upload/";
	
		/**
		 * Naming convention.
		 */
			/**
			 * addition for controller name.
			 */
			const addControllerName="Controller";
			
		/**
		 * params
		 */
			const cookieParams="/";
			const sslCookieParams="/";
			
		/**
		 * ajax view
		 */
			const ajaxView="run_exe";
			const subAjaxView="sub_exe";
	}		
	
	class AppConfig implements AppConfigRunnable{
		
		/**
		 * @var AppConfig
		 */
		public static $config;
		/**
		 * path to app home from browser.
		 * ブラウザからアプリのホームへのパス
		 * @var unknown_type
		 */
		static $appHomeFromBrowserPath="";
		
		/**
		 * path
		 * @return string
		 */
			protected static function getAppHome(){
				return appHome;
			}
			/**
			 * Directory name for the library.
			 * ライブラリのディレクトリ名
			 */
			public static function getLibPath(){
				return static::getAppHome()."lib/";
			}
			/**
			 * Directory name for the application.
			 * アプリケーションのディレクトリ名
			 */
			public static function getAppPath(){
				return static::getAppHome()."app/";
			}
			/**
			 * Directory name for the resource.
			 * リソースのディレクトリ名
			 */
			public static function getResourcePath(){
				return static::getAppHome()."resource/";
			}
			/**
			 * Directory name for the config.
			 */
			public static function getConfigPath(){
				return static::getAppHome()."config/";
			}
			
			
			/**
			 * view path.
			 */
			public static function getViewPath(){
				return static::getAppHome()."view/";
			}
			public static function getModelPath(){
				return static::getAppHome()."model/";
			}
			public static function includeModel($list){
				for($i=0;$i<count($list);$i++){
					require_once static::getModelPath().$list[$i].".php";
				}
			}
			
			
			/**
			 * @return string
			 */
			
			public static function getResourcePathFromBrowser(){
				return self::$appHomeFromBrowserPath.static::getResourcePath();
			}
			public static function getImagePath(){
				return static::getResourcePathFromBrowser()."image/";
			}
			public static function getExtLibPath(){
				return static::getLibPath()."ext/";
			}
			public static function getImageSavePath(){
				return static::getResourcePath()."image/upload/";
			}
			public static function getLogPath(){
				return static::getResourcePath()."logs/";
			}
			public static function getStringPath(){
				return static::getResourcePath()."string/";
			}
			public static function parseAuthUserPassword($id){
				return substr(md5($id),0,20);
			}
			
			
			
			/**
			 * Get host.
			 * ホストを取得
			 * @return string
			 */
			public static function getHost(){
				return "http://".$_SERVER['HTTP_HOST']."/";
			}
			/**
			 * Get ssl host.
			 * sslのホストを取得
			 * @return
			 */
			public static function getSslHost(){
				return "https://".$_SERVER['HTTP_HOST']."/";
			}
			/**
			 * Check ssl access.
			 * SSL接続か調べる
			 * @return boolean true=>ssl
			 */
			public static function isSsl(){//breaks
				//	return self::$ssl;
				//if (isset($_SERVER['HTTP_VIA'])&&strpos($_SERVER['HTTP_VIA'], 'ss1.coressl.jp:3128') !== false ) {
				if ( (false === empty($_SERVER['HTTPS']))&&('off' !== $_SERVER['HTTPS']) ) {
					return true;
				}
				return false;
			}
			/**
			 * 通常アクセスへリダイレクト
			 * @param DBDriver $db
			 */
			public static function redirectHost($db){
				header("Location: ".AppConfig::getHost().self::redirectUrl."?".self::getRedirectIndex()."=".self::createRedirectCode($db));
				exit();
			}
			/**
			 * 暗号化したGetIndexを生成
			 * @return string
			 */
			public static function getRedirectIndex(){
				return substr((md5($_SERVER['HTTP_USER_AGENT'])), 0, 10);
			}
			/**
			 * @param DBDriver $db
			 * @return string
			 */
			public static function createRedirectCode($db){
				static::includeModel(array("redirector"));
				$redirectCode=md5(strtotime("now").$_SERVER['HTTP_USER_AGENT'].HtmlHelper::getEscapeSessionId());
				$model=Redirector::createModel(array(Redirector::id=>$redirectCode,
					Redirector::session_id=>HtmlHelper::getEscapeSessionId(),
					Redirector::date=>strtotime("20 second")));
				Redirector::insert($db, $model);
				return $redirectCode;
			}
			
			/**
			 * redirect ro ssl.
			 */
			public static function redirectSsl(){
				header("Location: ".AppConfig::getSslHost());
				exit();
			}
			
			
			public static function init(){
				date_default_timezone_set('Asia/Tokyo');
				ini_set('session.gc_maxlifetime', '1800');
				ini_set('session.gc_probability','1');
				ini_set('session.gc_divisor', '100');
				ini_set('session.save_path',static::getResourcePath().'session');
				ini_set( 'display_errors' , 1 );
			}			
	}
	AppConfig::$config=new AppConfig();
	
?>