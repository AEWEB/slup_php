<?php
require_once AppConfig::getExtLibPath().'/facebook/facebook.php';
/**
 * class for Facebook auth.
 */
class Facebook_user extends Sl_user implements AuthModel{

	/**
	 * @var Facebook
	 */
	private  static $facebook=null;
	
	
	public static function getTable(){
		return "sl_user";
	}
	
	/**
	 * @return Ambigous <Twitter_user, NULL, Facebook_user, ModelRunnable, unknown>|NULL
	 */
	public static function runAuth() {
		if (!static::isApi()) {
			header("Location: ".static::getFacebook()->getLoginUrl(static::getLoginUrlParam()));
			exit();
		} 
		try {
			return static::createModel(static::getFacebook()->api('/me'));
		} catch (FacebookApiException $e) {
		}
		$this->remove();
		return null;
	}
	/**
	 * To check whether API can be use.
	 * apiが使えるか
	 * @return boolean
	 */
	public static function isApi(){
		return !static::getFacebook()->getUser()? false:true;
	}
	/**
	 * @return string[]
	 */
	protected static function getLoginUrlParam() {
		return array('canvas' => 1,'fbconnect' => 0,'req_perms' => 'status_update,publish_stream');
	}
	
	/**
	 * @param Object $data
	 * @return Twitter_user|NULL
	 */
	public static function createModel($data=null){
		if($data!==null){
			$model=new Facebook_user();
			$model->set(Facebook_user::id,$data["id"]);
			$model->set(Facebook_user::name,$data["name"]);
			$model->set(Facebook_user::imageurl, static::parseImageUrl($data["id"]));
			return $model;
		}
		return parent::createModel();
	}
	public static function parseImageUrl($id){
		return "https://graph.facebook.com/".$id."/picture";
	}
	public static function getFacebook(){
		if(self::$facebook===null){
			self::$facebook= new Facebook(array(
				'appId'  =>AppConfigRunnable::facebook_appId,
				'secret' =>AppConfigRunnable::facebook_secret,
				'cookie' => true));
		}
		return self::$facebook;
	}
	/**
	 * remove auth info.
	 * 認証情報を消去
	 * @return void
	 */
	public static function remove(){}
	/**
	 * (non-PHPdoc)
	 * @see AuthRunnable::get()
	 */
	public static function getUrl($url ,$param){return null;}
	
	/**
	 * @return string
	 */
	public static function getIdIndex(){
		return AppConfigRunnable::facebook_idIndex;
	}
	/**
	 * @return string
	 */
	public static function getCareerId(){
		return AppConfigRunnable::facebook_careerId;
	}

	/**
	 * @see AuthRunnable::getSaveIndex()
	 */
	public static function getSaveIndex(){
		return AppConfigRunnable::facebook_saveIndex;
	}
}

?>