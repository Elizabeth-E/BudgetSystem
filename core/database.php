<?php
namespace Framework;

class Database extends \MySQLi
{
    private static $instance = null;
	
	public static $host = "";
	public static $user = "";
	public static $password = "";
	public static $database = "";

    public function __construct(string $host, string $user, string $password, string $database)
    {
		parent::__construct($host, $user, $password, $database);
	}

    public static function getInstance() : Database
    {
        if (self::$instance == null)
        {
            self::$instance = new self(self::$host, self::$user, self::$password, self::$database);
        }
		
		return self::$instance;
    }
    
    private function __clone() {} // Prevent cloning of object

    private function __wakeup() {} // Prevent serialization
}
?>