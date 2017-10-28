<?php
namespace SimpleAdmin\Controller;
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

	public function __construct() {
		$this->database = Database::getInstance();
		$this->init_twig();
	}

	private function init_twig() {
		$loader = new Twig_Loader_Filesystem(BASE_URI . "public/views");
		$loader->addPath(BASE_URI . 'public', 'public');
		$this->twig = new Twig_Environment($loader, ['debug' => true]);
		$this->twig->addExtension(new Twig_Extension_Debug());

		$assets_function = new Twig_Function('assets', function ($file) {
			return ASSETS . $file;
		});
		$this->twig->addFunction($assets_function);
	}

	function redirect($url, $permanent = false) {
		if($permanent) {
			header('HTTP/1.1 301 Moved Permanently');
		}
		header('Location: '. BASE_URL . $url);
		exit();
	}

}