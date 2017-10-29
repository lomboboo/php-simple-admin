<?php
namespace SimpleAdmin\Controller;

use Application;
use SimpleAdmin\Service\Database;

class LoginController {

	private $database;
	private $router;
	function __construct() {
		$this->database = Database::getInstance();
		$this->router = Application::$router;
	}

	public function login(){
		$error = null;
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			if ( isset($_POST['username']) && isset($_POST['password']) ){

				$username = $_POST['username'];
				$password = sha1($_POST['password']);
				$this->database->query('SELECT username, password FROM users WHERE username=:username AND password=:password');
				$this->database->bind(':username', $username);
				$this->database->bind(':password', $password);
				$user = $this->database->single();

				if ( !empty($user) ){
					$_SESSION['username'] = $username;
					$_SESSION["authorized"] = true;

					$index_url = $this->router->generate('index');
					$this->redirect($index_url);
				} else {
					$error = "Wrong credentials";
				}
			}
		}
		echo Application::$twig->render('login.html.twig', [
			"error" => $error
		]);
	}

	public function authenticate(){
		return  isset($_SESSION["authorized"]) && $_SESSION["authorized"] === true;
	}

	function redirect($url, $permanent = false) {
		if($permanent) {
			header('HTTP/1.1 301 Moved Permanently');
		}
		header('Location: ' . $url);
		exit();
	}

	function get_current_user(){
		return $_SESSION['username'];
	}

	public function logout(){
		unset($_SESSION['username']);
		unset($_SESSION['authorized']);
		session_destroy();

		$index_url = $this->router->generate('login');
		$this->redirect($index_url);
	}

}