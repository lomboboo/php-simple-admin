<?php
namespace SimpleAdmin\Model;

use SimpleAdmin\Service\Database;

class UserModel {
	private $database;

	function __construct() {
		$this->database = Database::getInstance();
	}

	function get_user($username, $password){
		$this->database->query("SELECT id, username, password FROM users WHERE username=:username AND password=:password");
		$this->database->bind('username', $username);
		$this->database->bind('password', sha1($password));
		$user = $this->database->single();
		return $user;
	}

	function update_password($new_password, $user_id){
		$this->database->query("UPDATE users SET password=:password WHERE id=:id");
		$this->database->bind('password', sha1($new_password));
		$this->database->bind('id', $user_id);
		$res = $this->database->execute();
		return $res;
	}

}