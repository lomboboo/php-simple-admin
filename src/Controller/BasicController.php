<?php
namespace SimpleAdmin\Controller;
use Application;
use SimpleAdmin\Service\Database;
use Twig_Extension_Debug;
use Twig_Function;
use Twig_Loader_Filesystem;
use Twig_Environment;

class BasicController {
	/**
	 * The Query to run against the FileSystem
	 * @var Twig_Environment
	 */
	public $twig;
	public $database;
	/**
	 * The Query to run against the FileSystem
	 * @var \AltoRouter
	 */
	public $router;

	public function __construct() {
		$this->database = Database::getInstance();
		$this->twig = Application::$twig;
		$this->router = Application::$router;
	}

	function redirect($url, $permanent = false) {
		if($permanent) {
			header('HTTP/1.1 301 Moved Permanently');
		}
		header('Location: '. BASE_URL . $url);
		exit();
	}

}