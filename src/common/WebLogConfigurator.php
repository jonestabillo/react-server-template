<?php
namespace com\glyphstudios\project\common;

class WebLogConfigurator implements \LoggerConfigurator {
	public function configure(\LoggerHierarchy $hierarchy, $input = null){
		global $configuration;
		
		/*******************************************
		 * For Main
		 *******************************************/
		// Create an appender for Main Transactions.
		$mainTransAppender = new \LoggerAppenderDailyFile('mainTransAppender');
		$mainTransAppender->setLayout(new \LoggerLayoutSimple());
		$mainTransAppender->setFile($configuration->log['mainTransFile']);
		$mainTransAppender->setDatePattern('Y-m-d');
		
		// Create an appender for Main Errors.
		$mainErrorAppender = new \LoggerAppenderDailyFile('mainErrorAppender');
		$mainErrorAppender->setLayout(new \LoggerLayoutSimple());
		$mainErrorAppender->setFile($configuration->log['mainErrorFile']);
		$mainErrorAppender->setDatePattern('Y-m-d');
		
		//Create Main Loggers.
		$mainTransLogger = $hierarchy->getLogger('mainTransLogger');
		$mainErrorLogger = $hierarchy->getLogger('mainErrorLogger');
		
		//Assign Main Appenders.
		$mainTransLogger->addAppender($mainTransAppender);
		$mainErrorLogger->addAppender($mainErrorAppender);
	
	}
}