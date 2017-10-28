<?php
namespace SimpleAdmin\Controller;

class IndexController extends BasicController {

	public function __construct() {
		parent::__construct();
	}

	public function index(){
		$this->database->query('SELECT * FROM users WHERE name = :name');
		$this->database->bind(':name', 'lomboboo');
		$user = $this->database->single();
		echo $this->twig->render('index.html.twig', [
			"title" => "Strona główna",
			"user" => $user
		]);

	}

}