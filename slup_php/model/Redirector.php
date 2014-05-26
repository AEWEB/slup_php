<?php
	class Redirector extends Model{
		
		const session_id="session_id";
		const date="date";
		
		private static $column=null;
		private static $list=array(self::id=>array(self::valueIndex=>"id"),
			self::session_id=>array(self::valueIndex=>"session_id"),
			self::date=>array(self::valueIndex=>"date"));

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
		 * リダイレクト結果をセット
		 * @param string $id
		 * @param DBDriver $db
		 */
		public static function setupRedirect($id,$db){
			$db->startTransaction();
			$column=static::createModel();
			if(count(($data=Redirector::find($db,($model=Redirector::createModel(array(self::id=>$id))),
				array(DBDriver::queryOptionIndex_condition=>array(0=>array(
					DBDriver::queryOptionIndex_val=>$column->get(self::date).CommonResources::rightLess.CommonResources::equal.strtotime("now"),
					DBDriver::queryOptionIndex_logic=>MySQLDriver::andSql))))))>0){
				session_id($data[0]->get(self::session_id));
				
				$db->delete($model,array(
					DBDriver::queryOptionIndex_condition=>array(0=>array(
						DBDriver::queryOptionIndex_val=>$column->get(self::date).CommonResources::leftLess.CommonResources::equal.strtotime("now"),
						DBDriver::queryOptionIndex_logic=>MySQLDriver::orSql
					))));
				$db->commit();
			}else{
				$db->rollback();
			}
		}
		
		
	}
?>