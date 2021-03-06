<?php

class Application {
	private static $contact_email = 'lomboboo@gmail.com';
	private static $debug = true;
	/**
	 * The Query to run against the FileSystem
	 * @var \AltoRouter
	 */
	public static $router;
	/**
	 * The Query to run against the FileSystem
	 * @var Twig_Environment
	 */
	public static $twig;

	private static function pathUrl($dir = __DIR__){

		$root = "";
		$dir = str_replace('\\', '/', realpath($dir));

		//HTTPS or HTTP
		$root .= !empty($_SERVER['HTTPS']) ? 'https' : 'http';

		//HOST
		$root .= '://' . $_SERVER['HTTP_HOST'];

		//ALIAS
		if(!empty($_SERVER['CONTEXT_PREFIX'])) {
			$root .= $_SERVER['CONTEXT_PREFIX'];
			$root .= substr($dir, strlen($_SERVER[ 'CONTEXT_DOCUMENT_ROOT' ]));
		} else {
			$root .= substr($dir, strlen($_SERVER[ 'DOCUMENT_ROOT' ]));
		}

		$root .= '/';

		return $root;
	}


	public static function bootstrap() {

		self::init();

		self::twig();

		self::error_handler();

		self::dispatch();

	}


	private static function init() {
		define('BASE_URL', self::pathUrl(__DIR__ . '/../'));

		define('DB_TYPE', 'mysql');
		define('DB_HOST', 'localhost');
		define('DB_NAME', 'simple_admin');
		define('DB_USER', 'root');
		define('DB_PASS', 'root');

		define("DS", DIRECTORY_SEPARATOR);
		define('BASE_URI', getcwd() . DS);
		define('ASSETS', BASE_URL . 'public' . DS );

		if (self::$debug) {
			ini_set('display_errors', 1);
			ini_set('display_startup_errors', 1);
			error_reporting(E_ALL);
		}
	}

	private static function twig() {
		$loader = new Twig_Loader_Filesystem(BASE_URI . "public/views");
		$loader->addPath(BASE_URI . 'public', 'public');

		self::$twig = new Twig_Environment($loader, ['debug' => true]);
		self::$twig->addExtension(new Twig_Extension_Debug());

		self::twig_extensions();
	}

	private static function twig_extensions(){
		$assets_function = new Twig_Function('assets', function ($file) {
			return ASSETS . $file;
		});
		$base_function = new Twig_Function('base', function ($file) {
			return BASE_URL . $file;
		});
		$generate_url_function = new Twig_Function('generate_url', function ($url_name) {
			return self::$router->generate($url_name);
		});

		self::$twig->addGlobal('session', isset($_SESSION) ? $_SESSION : null);
		self::$twig->addFunction($assets_function);
		self::$twig->addFunction($base_function);
		self::$twig->addFunction($generate_url_function);
	}


	private static function dispatch() {
		self::$router = new AltoRouter();
		$url_path = preg_replace('/\/$/i', '', parse_url(BASE_URL, PHP_URL_PATH));
		if ( $url_path && $url_path !== "/" ){
			self::$router->setBasePath($url_path);
		}

		self::set_routes();

		$match = self::$router->match();
		self::handle_controller_action($match);
	}

	public static function handle_controller_action($match){
		list( $controller, $action ) = explode( '#', $match['target'] );

		if ( is_callable(array($controller, $action)) ) {

			$obj = new $controller();
			call_user_func_array(array($obj,$action), array($match['params']));

		} else if ($match['target']==''){
			echo 'Error: no route was matched';
		} else {
			echo 'Error: can not call '.$controller.'#'.$action;
		}
	}

	private static function set_routes(){
		self::$router->map('GET', '/', 'SimpleAdmin\Controller\IndexController#index', 'index');
		self::$router->map('GET', '/dashboard', 'SimpleAdmin\Controller\IndexController#dashboard', 'dashboard');
		self::$router->map('GET|POST', '/login', 'SimpleAdmin\Controller\LoginController#login', 'login');
		self::$router->map('GET', '/logout', 'SimpleAdmin\Controller\LoginController#logout', 'logout');
		self::$router->map('GET|POST', '/settings/password', 'SimpleAdmin\Controller\SettingsController#password', 'password');
		self::$router->map('GET|POST', '/settings/upload/logo', 'SimpleAdmin\Controller\SettingsController#upload_logo', 'upload_logo');
	}

	public static function error_handler(){
		set_error_handler(['Application', 'my_error_handler']);
	}

	public static function my_error_handler($e_number, $e_message, $e_file, $e_line, $e_vars) {

		$message = "An error occurred in script '$e_file' on line $e_line: $e_message";

		$message .= print_r($e_vars, 1);

		if (self::$debug) { // Show the error.
			//error_log ($message, 0);
			throw new \ErrorException($e_message, 0, $e_number, $e_file, $e_line);

		} else {
			$headers = "From: admin@localhost.com";
			$headers .= "MIME-Version: 1.0";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1";
			$res = error_log($message, 1, self::$contact_email, $headers); // Send email.

			// Only print an error message if the error isn't a notice or strict.
			if ( ($e_number != E_NOTICE) && ($e_number < 2048)) {
				echo '<div class="error">A system error occurred. We apologize for the inconvenience.</div>';
			}

		}

	}
}