<?php
namespace SimpleAdmin\Controller;

use SimpleAdmin\Model\UserModel;
use upload;

class SettingsController extends BasicController {
	private $user;
	function __construct() {
		parent::__construct();
		$this->user = new UserModel();
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
					$user = $this->user->get_user($username, $password);

					if ( !empty($user) ) {

						$res = $this->user->update_password($new_password, $user['id']);

						if ( $res ){
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
		$error = null;
		if ( $_SERVER['REQUEST_METHOD'] === 'POST' ){
			if ( isset($_FILES['logo_file']) ){
				$handle = new upload($_FILES['logo_file']);
				if ($handle->uploaded) {
					$handle->file_new_name_body   = 'image_resized';
					//TODO: database handle
					$handle->process(BASE_URI . 'public/uploads/logo/');
					if ($handle->processed) {
						echo 'image resized';
						$handle->clean();
					} else {
						echo 'error : ' . $handle->error;
					}
				}
			}
		}
		echo $this->twig->render('upload_logo.html.twig',[
			'title' => "Upload logo"
		]);
	}

}