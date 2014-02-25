<?php
	class Redirector extends SlModel{
		/**
		 * @var string
		 */
		private $sessionId;
		/**
		 * @var string
		 */
		private $date;
		
		/**
		 * @var Redirector
		 */
		private static $column;
		
		/**
		 * @return SlUser
		 */
		public static function createColumnModel(){
			if(self::$column===null){
				self::$column=parent::createColumnModel();
			}
			return self::$column;
		}
		public static function getTable(){
			return "redirector";
		}
		public static function getColumnArray(){
			return array(
					self::nameIndex=>array("id","sessionId","date"),
					self::valueIndex=>array("id","session_id","date"),
					self::valueSurroundIndex=>array(CommonResources::quote,CommonResources::quote,CommonResources::nullCharacter),
					self::findIndex=>array(true,true,false));
		}
		
		/**
		 * リダイレクト結果をセット
		 * @param string $id
		 * @param DBDriver $db
		 */
		public static function setupRedirect($id,$db){
			$db->startTransaction();
			$column=static::createColumnModel();
			if(count(($data=Redirector::findById($db, $id,SqlSyntax::getAnd().$column->getDate().CommonResources::rightLess.
				CommonResources::equal.strtotime("now"))))>0){//データが存在する
				session_id($data[0]->getSessionId());
				//有効期限切れを全て削除
				$db->delete($column,$column->getSessionId().CommonResources::equal.CommonResources::quote.
					$data[0]->getSessionId().CommonResources::quote.SqlSyntax::getOr().$column->getDate().CommonResources::leftLess.strtotime("now"));
				$db->commit();
			}else{
				$db->rollback();
			}
		}
		
		/**
		 * get session id
		 * @return string
		 */
		public function getSessionId() {
			return $this->sessionId;
		}
		/**
		 * get date
		 * @return string
		 */
		public function getDate(){
			return $this->date;
		}
		public function setSessionId($sessionId){
			$this->sessionId=$sessionId;
		}
		public function setDate($date){
			$this->date=$date;
		}
		
	}
?>