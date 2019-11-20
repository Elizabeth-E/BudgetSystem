<?php
use Framework\Dispatcher;

// Bootstrap the application
include_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . "bootstrap.php";

// The Dispatcher delegates controll to the corresponding Controller and Action based on the URL
$controller = new Dispatcher($_GET);
$controller = $controller->createInstance();
$controller->dispatch();
?>