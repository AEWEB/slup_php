<?php
class Sl_user extends Model{
	const name="name";
	const imageurl="imageurl";
	const password="password";
	const restriction="restriction";
	const date="date";
	const mid="m_id";
	const device="device";

	/**
	 * @var SlUser
	 */
	private static $list=array(
		self::id=>array(self::valueIndex=>"sl_id"),
		self::name=>array(self::valueIndex=>"sl_name"),
		self::imageurl=>array(self::valueIndex=>"sl_imageurl"),
		self::password=>array(self::valueIndex=>"sl_password"),
		self::restriction=>array(self::valueIndex=>"sl_restriction"),
		self::date=>array(self::valueIndex=>"sl_date"),
		self::mid=>array(self::valueIndex=>"sl_m_id"),
		self::device=>array(self::valueIndex=>"sl_device"));
	
	private static $column=null;

	public static function getColumnArray(){
		return self::$list;
	}
	public static function getColumn(){
		return self::$column;
	}
	/**
	 * @param ModelRunnable $model
	 */
	public static function setColumn($model){
		self::$column=$model;
	}
	/**
	 * @param DBDriver $db
	 * @param string $id
	 * @param string $where
	 * @return SlUser[]
	 */
	public static function findByIdSession($db,$id,$where=null){
		$columnModel=static::createModel();
		if($where===null){
			$where=CommonResources::nullCharacter;
		}
		return static::findBy($db,self::id,$id,SqlSyntax::getAnd().$columnModel->get(self::restriction).CommonResources::leftLess.AppConfigRunnable::restrictionLoginValue.$where);
	}
	/**
	 * @param DBDriver $db
	 * @param string $id
	 * @param string $password
	 * @return Sl_user[]
	 */
	public static function findByIdLogin($db,$id,$password){
		$columnModel=static::createModel();
		return static::findByIdSession($db, $id,SqlSyntax::getAnd().$columnModel->get(self::password).CommonResources::equal.CommonResources::quote.$password.CommonResources::quote);
	}
	
}
?>