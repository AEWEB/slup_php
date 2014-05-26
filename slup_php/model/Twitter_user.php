<?php
require_once AppConfig::getExtLibPath().'twitteroauth.php';
/**
 * class for Twitter auth.
 */
class Twitter_user extends Sl_user implements AuthModel{

	
	public static function getTable(){
		return "sl_user";
	}
	
	/**
	 * Run auth.
	 * 認証を実行
	 * @return AuthUser||null
	 */
	public static function runAuth() {
		if (!isset($_REQUEST['oauth_token'])||!isset($_REQUEST['oauth_verifier'])||(isset($_REQUEST['oauth_token'])
				&& $_SESSION['oauth_token'] !== $_REQUEST['oauth_token'])) {//Twitter info is invalid.
			$connection = new TwitterOAuth(AppConfigRunnable::twitter_consumerKey,AppConfigRunnable::twitter_consumerSecret);
			$request_token = $connection->getRequestToken(AppConfigRunnable::twitter_callbackUrl);
			$_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
			$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
			if ($connection->http_code==200) {
				/* Build authorize URL and redirect user to Twitter. */
				$url = $connection->getAuthorizeURL($token);
				header('Location: ' . $url);
				exit();
			}
			unset($_SESSION['oauth_token']);
			unset($_SESSION['oauth_token_secret']);
			$this->remove();
			return null;
		}
		$connection = new TwitterOAuth(AppConfigRunnable::twitter_consumerKey,AppConfigRunnable::twitter_consumerSecret, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
		$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
		$_SESSION['access_token'] = $access_token;
		$json=$connection->get('account/verify_credentials');
		$list=get_object_vars($json);
		if(isset($list["errors"])){
			return null;
		}
		return static::createModel($json);
	}	
	/**
	 * @param Object $data
	 * @return Twitter_user|NULL
	 */
	public static function createModel($data=null){
		if($data!==null){
			$model=new Twitter_user();
			$model->set(Twitter_user::id,mb_convert_encoding($data->screen_name,AppConfigRunnable::character, "UTF-8"));
			$model->set(Twitter_user::imageurl, mb_convert_encoding($data->profile_image_url,AppConfigRunnable::character, "UTF-8"));
			$model->set(Twitter_user::name, mb_convert_encoding($data->name,AppConfigRunnable::character, "UTF-8"));
			return $model;
		}
		return parent::createModel();
	}
	/**
	 * To check whether API can be use.
	 * apiが使えるか
	 * @return boolean
	 */
	public static function isApi(){
		return isset($_SESSION['access_token']) || isset($_SESSION['access_token']['oauth_token']) || isset($_SESSION['access_token']['oauth_token_secret']);
	}

	/**
	 * remove auth info.
	 * 認証情報を消去
	 * @return void
	 */
	public static function remove(){
		unset($_SESSION['access_token']);
	}
	/**
	 * (non-PHPdoc)
	 * @see AuthRunnable::get()
	 */
	public static function getUrl($url ,$param){
		if(!$this->isApi()){
			return null;
		}
		$access_token = $_SESSION['access_token'];
		$connection = new TwitterOAuth($this->getConsumerKey(),$this->getConsumerSecret(), $access_token['oauth_token'], $access_token['oauth_token_secret']);
		return $connection->get($url,$param);
	}
	/**
	 * @return string
	 */
	public static function getIdIndex(){
		return AppConfigRunnable::twitter_idIndex;
	}
	/**
	 * @return string
	 */
	public static function getCareerId(){
		return AppConfigRunnable::twitter_careerId;
	}

	/**
	 * @see AuthRunnable::getSaveIndex()
	 */
	public static function getSaveIndex(){
		return AppConfigRunnable::twitter_saveIndex;
	}
}

?>