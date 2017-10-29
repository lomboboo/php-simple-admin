<?php
namespace SimpleAdmin\Controller;

class IndexController extends BasicController {

	public function __construct() {
		parent::__construct();
	}

	public function index(){
		echo $this->twig->render('index.html.twig', [
			"title" => "Strona główna"
		]);

	}

	public function dashboard(){
		echo $this->twig->render('dashboard.html.twig', [
			"title" => "Dashboard",
		]);
	}

}