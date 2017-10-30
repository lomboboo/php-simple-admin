<?php
namespace SimpleAdmin\Controller;
use Application;
use SimpleAdmin\Service\Database;
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
	public $current_username;

	public function __construct() {

		$this->database = Database::getInstance();
		$this->twig = Application::$twig;
		$this->router = Application::$router;
		$login = new LoginController();
		if ( !$login->authenticate() ){
			$login_url = $this->router->generate('login');
			$this->redirect($login_url);
		}
		$this->current_username = $login->get_current_user();
	}

	function redirect($url, $permanent = false) {
		if($permanent) {
			header('HTTP/1.1 301 Moved Permanently');
		}
		header('Location: ' . $url);
		exit();
	}

}