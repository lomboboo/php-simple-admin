<?php
require_once 'src/Application.php';
if (file_exists('vendor/autoload.php')) {
	require_once 'vendor/autoload.php';
}
Application::bootstrap();

