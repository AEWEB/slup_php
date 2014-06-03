<?php
require_once AppConfig::getAppPath()."scaffoldBase.php";
	abstract class ScaffoldAjax extends ScaffoldBase{
		
		public function ScaffoldAjax(){
			parent::ScaffoldBase();
		}
		/**
		 * @see ScaffoldBase::setupPagerParams()
		 */
		protected function setupPagerParams(){
			$this->setPager((($page=HtmlHelper::getPostParam(self::pagerIndex))!==null?$page:"0"));
		}
		/**
		 * @see Controller::getActionParam()
		 */
		protected function getActionParam() {//
			return HtmlHelper::getPostParam($this->getActionIndex());
		}
		/**
		 * @see ScaffoldBase::setupModelId()
		 */
		protected function setupModelId(){
			$this->getModel()->set(ModelRunnable::id,HtmlHelper::getPostParam(self::edit_idIndex));
		}
		
	}
?>