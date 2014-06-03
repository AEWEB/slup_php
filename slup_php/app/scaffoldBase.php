<?php
	abstract class ScaffoldBase extends ApplicationBase{
		
		/**
		 * @var ModelRunnable[]
		 */
		private $models;
		/**
		 * @var ModelRunnable
		 */
		private $model;
		
		/**
		 * @var string
		 */
		private $pager;
		const pagerIndex="page";
		
		/**
		 * @var int
		 */
		private $modelCount=0;
		
		public function ScaffoldBase(){
			parent::ApplicationBase();
			$this->setupUseModel();
		}
		
		/**
		 * abstract method.
		 */
		/**
		 * Define the model to be used in the controller.
		 * @return void
		 */
		protected abstract function setupUseModel();
		/**
		 * DB access methods for used to list.
		 * @return void
		 */
		protected abstract function setupShowModels();
		/**
		 * DB access methods for used to edit.
		 * @return boolean
		 */
		protected abstract function setupEditModel();
		/**
		 * Get the total number of model.
		 * @return int
		 */
		protected abstract function getModelsAllCount();
		/**
		 * @return string
		 */
		protected abstract function createSuccess();
		/**
		 * @return string
		 */
		protected abstract function updateSuccess();
		/**
		 *@return string
		 */
		protected abstract function destroySuccess();

		
		protected function setupPagerParams(){
			$this->pager=(($page=HtmlHelper::getGetParam(self::pagerIndex))!==null?$page:"0");
		}
		const edit_idIndex="edit_no";
		protected function setupModelId(){
			$this->model->set(ModelRunnable::id,HtmlHelper::getGetParam(self::edit_idIndex));
		}
		public function create(){
			$this->setModel($this->getModel()->formCheck());
			if($this->getModel()->isValidation()){
				Model::putErrorMessage(CommonResources::dataRegistSuccess);
				return $this->createSuccess();
			}
		}
		public function show(){
			$this->setupPagerParams();
			$this->setupShowModels();
			if(count($this->models)<1){
				Model::putErrorMessage(CommonResources::notDateRegist);
				$this->setModelCount(1);
			}else{
				$this->setModelCount($this->getModelsAllCount());
			}
		}
		public function edit(){
			$this->setupModelId();
			$this->setupEditModel();
		}
		public function update(){
			$this->setupModelId();
			$this->setupEditModel();
			$editModel=$this->getModel();
			$this->setModel($this->getModel()->formCheck());
			if($this->getModel()->isValidation()){
				Model::putErrorMessage(CommonResources::dataUpdateSuccess);
				$this->getModel()->set(ModelRunnable::id,$editModel->get(ModelRunnable::id));
				return $this->updateSuccess();
			}else if($this->getModel()->isFirstAccess()){
				$editModel->resetGenerateForm($editModel);
				$this->setModel($editModel);
			}else{
				$this->getModel()->set(ModelRunnable::id,$editModel->get(ModelRunnable::id));
			}
		}
		public function destroy(){
			$this->setupModelId();
			$this->setupEditModel();
			Model::putErrorMessage(CommonResources::dataDeleteSuccess);
			return $this->destroySuccess();
		}
		
		
		/**
		 * @see ApplicationBase::getRootList()
		 */
		public function getRootList(){
			return array("show"=>"show","create"=>"create","edit"=>"edit","update"=>"update","destroy"=>"destroy");
		}
		
		/**
		 * @return ModelRunnable
		 */
		public function getModels(){
			return $this->models;
		}
		public function setModels($models){
			$this->models=$models;
		}
		
		public function getModelCount(){
			return $this->modelCount;
		}
		public function setModelCount($modelCount){
			$this->modelCount=$modelCount;
		}
		public function getPager(){
			return $this->pager;
		}
		public function setPager($pager){
			$this->pager=$pager;
		}
		/**
		 * @return ModelRunnable
		 */
		public function getModel(){
			return $this->model;
		}
		public function setModel($model){
			$this->model=$model;
		}
		
	}
?>