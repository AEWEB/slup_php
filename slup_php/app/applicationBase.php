<?php
	abstract class ApplicationBase extends Controller{
		/**
	 	* constructor
	 	*/
		public function ApplicationBase(){
			parent::Controller();
		}
		public function getRootList(){
			return array();
		}
	}
?>