<?php

namespace com\glyphstudios\project\common;
use com\glyphstudios\project\common\WebLogConfigurator;

class Utility{

	static final function errorLogger($errorLogger, $ex){
		try{
			$timestamp = date('Y-m-d') . 'T' . date('H:i:s') . 'Z';
			
			\Logger::configure(NULL, new WebLogConfigurator());
			$log = \Logger::getLogger($errorLogger);
			$log->error('[' . $timestamp . '] ' . $ex->getMessage() . PHP_EOL . '[Stack Trace]: ' . PHP_EOL . $ex->getTraceAsString());
			
			return true;
		}catch(\Exception $ex){
			throw $ex;
		}
	}

	static final function transLogger($transLogger, $transanctionInfo){
		try{
			$timestamp = date('Y-m-d') . 'T' . date('H:i:s') . 'Z';
			
			\Logger::configure(NULL, new WebLogConfigurator());
			
			$log = \Logger::getLogger($transLogger);
			$log->info('[' . $timestamp . '] ' . $transanctionInfo);
			
			return true;
		}catch(\Exception $ex){
			throw $ex;
		}
	}
}