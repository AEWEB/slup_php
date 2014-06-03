<?php
	class Slimage extends Model{
		
		const tagName="img";
		/**
		 * db column
		 */
		const title="title";
		const size="size";
		const type="type";
		const user="user";
		
		const name="name";
		const filePath="filePath";
		
		
		
		/**
		 * @var Image
		 */
		private static $list=array(
			self::id=>array(self::valueIndex=>"image_id"),
			self::title=>array(self::valueIndex=>"image_title"),
			self::size=>array(self::valueIndex=>"image_size"),
			self::type=>array(self::valueIndex=>"image_type"),
			self::user=>array(self::valueIndex=>"image_user"));
		
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
		 * @param Slimage $image
		 * @return string|NULL
		 */
		public static function isUploadFile($image){
			if(!isset($_FILES[($name=$image->get(self::name))])
				||($img_temp=$_FILES[$name]["tmp_name"])==CommonResources::nullCharacter
				||!static::isImageSec($img_temp,$image)){
				return CommonResources::imageWrongError;
			}else if(self::isImageSize($name,$image)){
				return CommonResources::imageSizeError;
			}else if(!($type = getimagesize($img_temp))){
				return CommonResources::imageTypeError;
			}else{
				$typeList=static::getImageTypeList();
				if(!isset($typeList[$type["mime"]])){
					return CommonResources::imageTypeError;
				}
				$image->set(self::type,$typeList[$type["mime"]]);
			}
			return null;
		}
		/**
		 * @param String $img_temp
		 * @param Slimage $image
		 */
		protected static function isImageSec($img_temp,$image){
			$image->set(self::filePath, $img_temp);
			if(preg_match('/<\\?php(.|\n)/i',file_get_contents($img_temp))){
				return false;
			}
			return true;
		}
		/**
		 * @param String $name
		 * @param Slimage $image
		 * @return boolean
		 */
		protected static function isImageSize($name,$image){
			$image->set(self::size,$_FILES[$name]["size"]);
			return $image->get(self::size)<=AppConfigRunnable::imageMaxSize;
		}
		protected static function getImageTypeList(){
			return array(
					"image/gif"=>".gif",
					"image/jpeg"=>".jpg",
					"image/png"=>".png"
			);
		}
		/**
		 * @param Slimage $image
		 */
		public static function run($image){
			move_uploaded_file($image->get(self::filePath),
				AppConfig::getImageSavePath().$image->get(self::id).$image->get(self::type));
		}
		/**
		 * @see SlModel
		 */
		public static function delete($db, $model){
			parent::delete($db, $model);
			try{
				unlink(AppConfig::getImageSavePath().$model->get(self::id).$model->get(self::type));
			}catch (Exception $e){}
		}
		/**
		 * @param Slimage $image
		 * @return string
		 */
		public static function getSrc($image){
			return AppConfig::getImagePath().AppConfigRunnable::imageUploadPath.$image->get(self::id).$image->get(self::type);
		}
		/**
		 * @param Slimage $image
		 */
		public static function getImageTag($image){
			require_once AppConfig::$config->getLibPath()."module/BuilderResource.php";
			return CustomTag::idCustomTag(self::tagName,$image->get(self::id).$image->get(self::type)," width".CommonResources::equal."200"." height".CommonResources::equal."200");
		}
		public static function replaceImageTag($str){
			require_once AppConfig::$config->getLibPath()."module/BuilderResource.php";
			return CustomTag::replaceIdCustomTag($str,self::tagName,"src=".BuilderResources::insertQuart.AppConfig::getImagePath().AppConfigRunnable::imageUploadPath);
		}
		public static function replaceToCustomTag($str){
			require_once AppConfig::$config->getLibPath()."module/BuilderResource.php";
			return CustomTag::replaceToIdTag($str,self::tagName, "src=".BuilderResources::insertQuart.AppConfig::getImagePath().AppConfigRunnable::imageUploadPath);
		}
	}
?>