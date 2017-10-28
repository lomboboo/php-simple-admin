<?php
require_once 'vendor/autoload.php';
require ('./includes/config.php');

$loader = new Twig_Loader_Filesystem(BASE_URI . "/views");
$twig = new Twig_Environment($loader);

echo $twig->render('index.twig.html', [
	"name" => "Roman!"
]);