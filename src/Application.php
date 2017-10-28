<?php

class Application {
	private static $contact_email = 'lomboboo@gmail.com';
	private static $debug = true;

	public static function bootstrap() {

		self::init();

		self::error_handler();

		self::dispatch();

	}


	private static function init() {
		define('BASE_URL', 'http://localhost/php_simple_admin/');

		define('DB_TYPE', 'mysql');
		define('DB_HOST', 'localhost');
		define('DB_NAME', 'simple_admin');
		define('DB_USER', 'root');
		define('DB_PASS', 'root');

		define("DS", DIRECTORY_SEPARATOR);
		define('BASE_URI', getcwd() . DS);

		if (self::$debug) {
			ini_set('display_errors', 1);
			ini_set('display_startup_errors', 1);
			error_reporting(E_ALL);
		}
	}

	private static function dispatch() {
		$router = new AltoRouter();
		$router->setBasePath('/php_simple_admin');
		$router->map('GET', '/', 'SimpleAdmin\Controller\IndexController#index');

		$match = $router->match();

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

	private static function error_handler(){
		set_error_handler(['Application', 'my_error_handler']);
	}

	private static function my_error_handler($e_number, $e_message, $e_file, $e_line, $e_vars) {

		$message = "An error occurred in script '$e_file' on line $e_line: $e_message";

		$message .= print_r($e_vars, 1);

		if (self::$debug) { // Show the error.

			echo '<div class="error">' . $message . '</div>';
			debug_print_backtrace();

		} else {

			error_log ($message, 1, self::$contact_email); // Send email.

			// Only print an error message if the error isn't a notice or strict.
			if ( ($e_number != E_NOTICE) && ($e_number < 2048)) {
				echo '<div class="error">A system error occurred. We apologize for the inconvenience.</div>';
			}

		}

	}
}