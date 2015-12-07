<?php
namespace com\glyphstudios\project\controller;

class Controller {
	protected $_controller;
	protected $_action;

    function __construct($controller, $action) {
		$this->_controller = $controller;
		$this->_action = $action;
	}
}
