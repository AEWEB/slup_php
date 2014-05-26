<?php
	class Sl_image extends Model{
		
		const tagName="img";
		const title="title";
		const size="size";
		const type="type";
		const user="user";
		//"image_id","image_title","image_size","image_type","image_user"
		
		/**
		 * @var Image
		 */
		private static $list=array(
			self::id=>array(self::valueIndex=>"image_id",self::updateIndex=>false,self::typeIndex=>self::validation_numeric,self::requireIndex=>true,
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
		
		
		
		/**
		 * @var ImageModel
		 */
		private static $column=null;
		

		/**
		 * @var ImageModel
		 */
		public static function createColumnModel(){
			if(self::$column===null){
				self::$column=parent::createColumnModel();
			}
			return self::$column;
		}
		public static function getTable(){
			return "slimage";
		}
	
		public function isUploadFile($name){
			if(!isset($_FILES[$name])
					||($this->name=$_FILES[$name]["tmp_name"])==CommonResources::nullCharacter
					||!self::isImageSec($this->name)){
				return CommonResources::imageWrongError;
			}else if(self::isImageSize($name)){
				return CommonResources::imageSizeError;
			}else if(!($type = getimagesize($this->name))){
				return CommonResources::imageTypeError;
			}else{
				$typeList=$this->getImageTypeList();
				if(!isset($typeList[$type["mime"]])){
					return CommonResources::imageTypeError;
				}
				$this->setType($typeList[$type["mime"]]);
			}
			return null;
		}
		
		public static function isImageSec($img_temp){
			if(preg_match('/<\\?php(.|\n)/i',file_get_contents($img_temp))){
				return false;
			}
			return true;
		}
		protected function isImageSize($name){
			$this->size=$_FILES[$name]["size"];
			return $this->size<=AppConfig::imageMaxSize;
		}
		protected function getImageTypeList(){
			return array(
					"image/gif"=>".gif",
					"image/jpeg"=>".jpg",
					"image/png"=>".png"
			);
		}
		public function run(){
			move_uploaded_file($this->name,
				AppConfigLib::getImageSavePath().$this->getId().$this->getType());
		}
		/**
		 * @see SlModel
		 */
		public static function delete($db, $model){
			parent::delete($db, $model);
			try{
				unlink(AppConfigLib::getImageSavePath().$model->getId().$model->getType());
			}catch (Exception $e){}
		}
		public function getSrc(){
			return AppConfigLib::getImagePath().AppConfig::imageUploadPath.$this->getId().$this->getType();
		}
		public function getImageTag(){
			require_once appHome.AppConfig::resourcePath."builderResource.php";
			return CustomTag::idCustomTag(self::tagName,$this->getId().$this->getType()," width".CommonResources::equal."200"." height".CommonResources::equal."200");
		}
		public static function replaceImageTag($str){
			require_once appHome.AppConfig::resourcePath."builderResource.php";
			return CustomTag::replaceIdCustomTag($str,self::tagName,"src=".BuilderResources::insertQuart.AppConfigLib::getImagePath().AppConfig::imageUploadPath);
		}
		public static function replaceToCustomTag($str){
			require_once appHome.AppConfig::resourcePath."builderResource.php";
			return CustomTag::replaceToIdTag($str,self::tagName, "src=".BuilderResources::insertQuart.AppConfigLib::getImagePath().AppConfig::imageUploadPath);
		}
	}
?>