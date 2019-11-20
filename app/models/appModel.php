<?php
namespace App\Models;

use \Framework\Database;

abstract class AppModel 
{
    protected $database = NULL;

    public function __construct()
    {
        // Singleton database object
        $this->database = $this->getDatabaseInstance();
    }

    private function getDatabaseInstance() : Database
    {
        // Set up the database configureation
        Database::$host = \Framework\getConfig("database.host");
        Database::$user = \Framework\getConfig("database.username");
        Database::$password = \Framework\getConfig("database.password");
        Database::$database =\Framework\getConfig("database.database");

        $database = Database::getInstance();
        if ($database->connect_errno <= 0)
        {
            return $database;
        }
        else
        {
            throw new Exception('Unable to connect to database [' . $database->connect_error . ']');
            return null;
        }
    }
}
?>