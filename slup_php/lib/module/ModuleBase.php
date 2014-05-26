<?php
	abstract class ModuleBase{
		
		const usualInfo="INFO";
		const errorInfo="ERROR";
		
		protected function log($logs){
			$this->outputLog(self::usualInfo, $logs);
		}
		protected function errorLog($logs){
			$this->outputLog(self::errorInfo, $logs);
		}
		protected function outputLog($info,$logs){
			error_log(date( "Y/m/d (D) H:i:s", time()).
				CommonResources::space.CommonResources::underscore.CommonResources::underscore.CommonResources::space.$info.
				CommonResources::space.CommonResources::underscore.CommonResources::underscore.CommonResources::space.$logs."\r\n", 
				3, $this->getOutputDir().$this->getOutputName());
		}
		protected function getOutputDir(){
			return AppConfig::getLogPath();
		}
		protected function getOutputName(){
			return get_called_class().".log";
		}
	}
?>