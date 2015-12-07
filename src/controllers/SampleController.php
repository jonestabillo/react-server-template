<?php
namespace com\glyphstudios\project\controller;
use com\glyphstudios\project\controller\Controller;

class SampleController extends Controller {
    
    public function sampleAction($requestBody){
		global $configuration;
		
		try{
			return "SAMPLE CONTROLLER";
		}catch(\Exception $ex){
			return FALSE;
		}
	}
}