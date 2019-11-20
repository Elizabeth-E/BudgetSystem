<?php
/*
	Bootstrap should be used to:
		- Define global constants;
		- Load configuration files;
		- Convenience (global) functions.
*/

namespace Config;

// Load base components
include_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . "paths.php";

include_once CONFIG . "app.php";
include_once CORE . "database.php";
include_once CORE . "bootstrap.php";
include_once CORE . "dispatcher.php";
include_once CORE . "templateEngine.php";

// Add own configuration here
?>