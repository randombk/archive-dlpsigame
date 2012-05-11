<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

require(ROOT_PATH . 'engine/libs/Smarty/Smarty.class.php');

class SmartyWrapper extends Smarty {
	protected $window = 'full';

	function __construct() {
		parent::__construct();
		$this->smartySettings();
	}

	function smartySettings() {
		$this->force_compile = false;
		$this->caching = false; #TODO: Set true for production
		$this->merge_compiled_includes = true;
		$this->compile_check = true; #TODO: Set false for production
		$this->php_handling = Smarty::PHP_REMOVE;

		$this->setCompileDir(ROOT_PATH . 'cache/');
		$this->setCacheDir(ROOT_PATH . 'cache/templates');
		$this->setTemplateDir(ROOT_PATH . 'pages/');
		//$this->loadFilter('output', 'trimwhitespace');
	}

	public function assign_vars($var, $nocache = true) {
		parent::assign($var, NULL, $nocache);
	}

	public function show($file) {
		$tplDir = $this->getTemplateDir();
		$this->compile_id = "en";

		parent::display($file);
	}

	public function display($file) {
		$this->compile_id = "en";
		parent::display($file);
	}
}
