<?php
	AppConfig::includeModel(array("Slimage"));
	class ImageForm extends Model{
		const image_1="image_1";
		const image_2="image_2";
		const image_3="image_3";
		
		const titleMax="50";
		
		/**
		 * @var SlUser
		 */
		private static $list=array(
			self::image_1=>array(self::outputIndex=>ModelResource::slimage_title,self::numMaxIndex=>self::titleMax,self::formIndex=>HtmlHelper::text),
			self::image_2=>array(self::outputIndex=>ModelResource::slimage_title,self::numMaxIndex=>self::titleMax,self::formIndex=>HtmlHelper::text),
			self::image_3=>array(self::outputIndex=>ModelResource::slimage_title,self::numMaxIndex=>self::titleMax,self::formIndex=>HtmlHelper::text));

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
	}	
	class ImageController extends ApplicationBase{
		
		/**
		 * @var Slimage[]
		 */
		private $imageList;
		
		/**
		 * @var ImageForm
		 */
		private $imageForm;
		/**
		 * @var int
		 */
		private $imageCount;
		
		const image_name_1="image_name_1";
		const image_name_2="image_name_2";
		const image_name_3="image_name_3";
		const imageShowCount=20;
		
		/**
		 * @see Controller::index()
		 */
		public function index(){
			if($this->getUser()===null){
				$this->redirector();
			}
			$this->imageForm=ImageForm::formCheck();
			if(ImageForm::isValidation()){
				$this->runUploadFile(self::image_name_1,ImageForm::image_1 ,"1");
				$this->runUploadFile(self::image_name_2, ImageForm::image_2 ,"2");
				$this->runUploadFile(self::image_name_3,ImageForm::image_3 , "3");
			}
			$start="0";
			if(($page=HtmlHelper::getGetParam("page"))!==null&&
				strval($page)===strval(intval($page))){
				$start=$page;
			}
			$this->showImage($start);
			$this->setupAuthSuccess();
			return "index";
		}
		/**
		 * @param String $name
		 * @param String $title
		 * @param String $num
		 * @return void
		 */
		protected function runUploadFile($name,$title,$num){
			$image=Slimage::createModel(array(Slimage::title=>$this->imageForm->get($title)));
			$image->set(Slimage::name, $name);
			if(($error=Slimage::isUploadFile($image))===null){
				$image->set(Slimage::user, $this->getUser()->get(Sl_user::id));
				$this->getDB(self::basicDbIndex)->startTransaction();
				Slimage::insert($this->getDB(self::basicDbIndex), $image);
				$image->set(Slimage::id, $this->getDB(self::basicDbIndex)->getLastInsertId($image));
				Slimage::run($image);
				$this->getDB(self::basicDbIndex)->commit();
				$this->imageForm->set($title, CommonResources::nullCharacter);
				ImageForm::resetGenerateForm($this->imageForm,$title);//reset title.
				Slimage::putErrorMessage(ImageString::getSuccessUpload($num));
			}else if($error===CommonResources::imageWrongError){//Incorrect image file Or file is not being sent.
			}else{
				Slimage::putErrorMessage($error);
			}
		}
		/**
		 * @param int $start
		 * @return void
		 */
		protected function showImage($start){
			$this->imageList=Slimage::find($this->getAuthDB(),($image=Slimage::createModel(array(Slimage::user=>$this->getUser()->get(Sl_user::id)))),
				array(DBDriver::queryOptionIndex_order=>array(array(DBDriver::queryOptionIndex_order_column=>Slimage::id)),
					DBDriver::queryOptionIndex_limitStart=>$start,DBDriver::queryOptionIndex_limitCount=>self::imageShowCount));
			if(count($this->imageList)<1){
				Slimage::putErrorMessage(CommonResources::notDateRegist);
				$this->imageCount=1;
			}else{
				$this->imageCount=Slimage::findByCount($this->getDB(self::basicDbIndex),$image,array(DBDriver::queryOptionIndex_projection=>array(Slimage::id)));
			}
		}
		public function delete(){
			if($this->getUser()===null||($id=HtmlHelper::getGetParam(Slimage::id))===null||strval($id)!==strval(intval($id))){
				$this->redirector();
			}
			$this->getDB(self::basicDbIndex)->startTransaction();
			if(count($imageList=Slimage::find($this->getDB(self::basicDbIndex), Slimage::createModel(array(
				Slimage::id=>$id,Slimage::user=>$this->getUser()->get(Sl_user::id)))))){
				Slimage::putErrorMessage(CommonResources::dataDeleteSuccess);
				Slimage::delete($this->getDB(self::basicDbIndex), $imageList[0]);
				$this->getDB(self::basicDbIndex)->commit();
			}else{
				Slimage::putErrorMessage("画像が見つかりませんでした。");
				$this->getDB(self::basicDbIndex)->rollback();
			}
			return $this->index();
		}
		
		/**
		 * @return ImageForm
		 */
		public function getImageForm(){
			return $this->imageForm;
		}
		/**
		 * @return int
		 */
		public function getImageCount(){
			return $this->imageCount;
		}
		/**
		 * @return Slimage[]
		 */
		public function getImageList(){
			return $this->imageList;
		}
		
		public function getRootList(){
			return array("delete"=>"delete");
		}
		
		
		
		
	}
?>