<?php
namespace Config;

// Use the DS to separate the directories in other defines
if (!defined("DS"))
{
    define("DS", DIRECTORY_SEPARATOR);
}

// URL location of the MVC system
define("BASE_URL", "http://" . $_SERVER['SERVER_NAME'] . "/budget_system");

// The full path to the directory which holds "src", WITHOUT a trailing DS.
define("ROOT", dirname(__DIR__));

// The full path to the directory which holds "src", WITHOUT a trailing DS.
define("CORE", ROOT . DS . "core" . DS);

// The actual directory name for the application directory.
define("APP_DIR", "app");

// Path to the application"s directory.
define("APP", ROOT . DS . APP_DIR . DS);

// Path to the config directory.
define("CONFIG", ROOT . DS . "config" . DS);

// Path to templating engine
define("TEMPLATING_ENGINE", ROOT . DS . "vendors" . DS . "smarty" . DS);

// Path to php mailer
define("PHP_MAILER", ROOT . DS . "vendors" . DS . "phpmailer" . DS . "src" . DS);

//Path to FPDF
define("FPDF", ROOT . DS . "vendors" . DS . "FPDF" . DS);

// Path to the temp directory
define("TMP", ROOT . DS . "tmp" . DS);

// Autoload paths
define("MODELS", APP . "models" . DS);
define("CONTROLLERS", APP . "controllers" . DS);
define("VIEWS", APP ."views" . DS);
define("AUTOLOAD_CLASSES", serialize([
    CORE,
    MODELS,
    CONTROLLERS,
    VIEWS
]));

/**
 * File path to the webroot directory.
 * To derive your webroot from your webserver change this to:
 *
 * define("WWW_ROOT", rtrim($_SERVER["DOCUMENT_ROOT"], DS) . DS);
 */
define("WWW_ROOT", ROOT . DS . "webroot" . DS);
?>