<?php
class Sl_user extends Model{
	const name="name";
	const imageurl="imageurl";
	const password="password";
	const restriction="restriction";
	const date="date";
	const mid="m_id";
	const device="device";	

	const minLenId=3;
	const maxLenId=20;
	const minLenPassword=4;
	const maxLenPassword=20;
	const minLenM_id=5;
	const maxLenM_id=80;
	
	
	/**
	 * @var SlUser
	 */
	private static $list=array(
		self::id=>array(self::valueIndex=>"sl_id",self::findIndex=>true,self::updateIndex=>false,self::typeIndex=>self::validation_ctypeAlnum_bar,
			self::outputIndex=>ModelResource::sl_user_id,self::numMinIndex=>self::minLenId,self::numMaxIndex=>self::maxLenId),
		self::name=>array(self::valueIndex=>"sl_name",self::findIndex=>true),
		self::imageurl=>array(self::valueIndex=>"sl_imageurl",self::findIndex=>true),
		self::password=>array(self::valueIndex=>"sl_password",self::typeIndex=>self::validation_ctypeAlnum,
			self::outputIndex=> ModelResource::sl_user_password,self::numMinIndex=>self::minLenPassword,self::numMaxIndex=>self::maxLenPassword),
		self::restriction=>array(self::valueIndex=>"sl_restriction",self::findIndex=>true),
		self::date=>array(self::valueIndex=>"sl_date",self::updateIndex=>false),
		self::mid=>array(self::valueIndex=>"sl_m_id",self::typeIndex=>self::validation_mailAdd,self::updateIndex=>false,
			self::outputIndex=> ModelResource::sl_user_m_id,self::numMinIndex=>self::minLenM_id,self::numMaxIndex=>self::maxLenM_id),
		self::device=>array(self::valueIndex=>"sl_device",self::findIndex=>true));	
	
	private static $column=null;

	public static function getColumnArray(){
		return self::$list;
	}
	public static function setColumnArray($list){
		self::$list=$list;
		self::$column=null;
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