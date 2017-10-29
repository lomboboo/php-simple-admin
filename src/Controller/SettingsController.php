<?php
/**
 * Created by PhpStorm.
 * User: lomboboo
 * Date: 29.10.17
 * Time: 18:52
 */

namespace SimpleAdmin\Controller;


class SettingsController extends BasicController {
	function __construct() {
		parent::__construct();
	}

	function password(){
		$error = null;
		$success = null;
		if ( $_SERVER['REQUEST_METHOD'] === 'POST' ){
			if ( isset($_POST['password']) && isset($_POST['new_password']) && isset($_POST['repeat_password']) ){
				$password = $_POST['password'];
				$new_password = $_POST['new_password'];
				$repeat_password = $_POST['repeat_password'];
				if ( $new_password !== $repeat_password ){
					$error = "New and repeated passwords does not match.";
				} else {
					$username = $this->current_username;
					$this->database->query("SELECT id, username, password FROM users WHERE username=:username AND password=:password");
					$this->database->bind('username', $username);
					$this->database->bind('password', sha1($password));
					$user = $this->database->single();

					if ( !empty($user) ) {
						$this->database->query("UPDATE users SET password=:password WHERE id=:id");
						$this->database->bind('password', sha1($new_password));
						$this->database->bind('id', $user['id']);
						$this->database->execute();
						$rows = $this->database->rowCount();

						if ( $rows > 0 ){
							$success = "Password was successfully changed.";
						} else {
							$error = "Database update error";
						}

					} else {
						$error = "Wrong current password";
					}
				}
			}
		}
		echo $this->twig->render('change_password.html.twig', [
			'title'=> "change password",
			'error' => $error,
			'success' => $success
		]);
	}

	function upload_logo(){
		echo $this->twig->render('upload_logo.html.twig',[
			'title' => "Upload logo"
		]);
	}

}