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
				return static::getResourcePath()."/image/upload/";
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