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
	 * user restriction.
	 * @var string
	 */
	const tempRestriction="15";
	const tempUserParam  = "temp";
	
	/**
	 * register form.
	 */
	const consentCheck="consent";
	const consentCheck_equals="consentCheck";
	const passwordConfirmation="passwordConfirmation";
	
	/**
	 * @var SlUser
	 */
	private static $list=array(
		self::id=>array(self::valueIndex=>"sl_id",self::updateIndex=>false,self::typeIndex=>self::validation_ctypeAlnum_bar,self::requireIndex=>true,
			self::outputIndex=>ModelResource::sl_user_id,self::numMinIndex=>self::minLenId,self::numMaxIndex=>self::maxLenId,self::formIndex=>HtmlHelper::text),
		self::name=>array(self::valueIndex=>"sl_name"),
		self::imageurl=>array(self::valueIndex=>"sl_imageurl"),
		self::password=>array(self::valueIndex=>"sl_password",self::typeIndex=>self::validation_ctypeAlnum,self::requireIndex=>true,
			self::outputIndex=> ModelResource::sl_user_password,self::numMinIndex=>self::minLenPassword,self::numMaxIndex=>self::maxLenPassword,
			self::formIndex=>HtmlHelper::text,self::formType=>HtmlHelper::password),
		self::restriction=>array(self::valueIndex=>"sl_restriction"),
		self::date=>array(self::valueIndex=>"sl_date",self::updateIndex=>false),
		self::mid=>array(self::valueIndex=>"sl_m_id",self::typeIndex=>self::validation_mailAdd,self::updateIndex=>false,
			self::outputIndex=>ModelResource::sl_user_m_id,self::numMinIndex=>self::minLenM_id,self::numMaxIndex=>self::maxLenM_id,self::formIndex=>HtmlHelper::text),
		self::device=>array(self::valueIndex=>"sl_device"));	
	
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
	
	public static function setupRegisterColumn(){
		self::$list[self::mid][self::requireIndex]=true;
		self::$list[self::id][self::requireIndex]=false;
		self::$list[self::password][self::requireIndex]=false;
		self::$column=null;
	}
	public static function setupRegisterCompleteColumn(){
		self::$list[self::consentCheck]=array(
			self::valueIndex=>"consentCheck",
			self::requireIndex=>true,
			self::outputIndex=>ModelResource::sl_user_consentCheck,
			self::typeIndex=>self::validation_equals,
			self::formIndex=>HtmlHelper::checkBox,
			self::updateIndex=>false,
			self::formIndexOption=>"id='label_".self::consentCheck."'",
			self::equalsIndex=>self::consentCheck_equals);
		self::$list[self::passwordConfirmation]=static::getPasswordConfirmationList();
		self::$column=null;
	}
	public static function getPasswordConfirmationList(){
		return array(
			self::valueIndex=>"passwordConfirmation",
			self::typeIndex=>self::validation_ctypeAlnum,
			self::numMinIndex=>self::minLenPassword,
			self::numMaxIndex=>self::maxLenPassword,
			self::requireIndex=>true,
			self::formIndex=>HtmlHelper::text,
			self::formType=>HtmlHelper::password,
			self::updateIndex=>false,
			self::outputIndex=>ModelResource::sl_user_passwordConfirmation);
	}
	public static function setupReissuePasswordColumn(){
		self::$list[self::id][self::requireIndex]=false;
		self::$list[self::passwordConfirmation]=static::getPasswordConfirmationList();
		self::$column=null;
	}
	
	/**
	 * @param DBDriver $db
	 * @param ModelRunnable $model
	 * @param string[] $options
	 * @return SlUser[]
	 */
	public static function findByIdSession($db,$model,$options=array()){
		$columnModel=$model->createModel();
		$options[DBDriver::queryOptionIndex_condition][]=array(
			DBDriver::queryOptionIndex_val=>$columnModel->get(Sl_user::restriction).CommonResources::leftLess.AppConfigRunnable::restrictionLoginValue,
			DBDriver::queryOptionIndex_logic=>MySQLDriver::andSql);
		return static::find($db,$model,$options);
	}
	/**
	 * @param DBDriver $db
	 * @param string $id
	 * @param string $password
	 * @return Sl_user[]
	 */
	public static function findByIdLogin($db,$id,$password,$options=array()){
		return static::findByIdSession($db,Sl_user::createModel(array(Sl_user::id=>$id,Sl_user::password=>$password)),$options);
	}
	
	/**
	 * 仮登録で24時間以上立っているものをすべて削除
	 * @param DBDriver $db
	 */
	public static function deleteExpiredData($db){
		$columModel=Sl_user::createModel();
		$option[DBDriver::queryOptionIndex_condition][]=
			array(DBDriver::queryOptionIndex_val=>$columModel->get(self::date).CommonResources::leftLess.CommonResources::equal.MySQLDriver::funcNow,
				DBDriver::queryOptionIndex_logic=>MySQLDriver::andSql);
		$db->delete(static::createModel(array(self::restriction=>self::tempRestriction)),$option);
	}

}
?>