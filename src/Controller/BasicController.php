<?php
namespace SimpleAdmin\Controller;
use Twig_Loader_Filesystem;
use Twig_Environment;

class BasicController {
	public $twig;

	public function __construct() {
		$this->init_twig();
	}

	private function init_twig() {
		$loader = new Twig_Loader_Filesystem(BASE_URI . "public/views");
		$this->twig = new Twig_Environment($loader);
	}

}