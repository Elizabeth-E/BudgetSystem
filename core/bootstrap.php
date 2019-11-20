<?php
// Set strict return types
declare(strict_types = 1);

/*
	Bootstrap should be used to:
		- Define global constants;
		- Load configuration files;
		- Convenience (global) functions.
*/

namespace Framework;

session_start(); // Start or resume sessions
ob_start(); // Allow Cookies

// Pretty debug printing
function debug($value)
{
	echo "<strong>DEBUG<br />(" . strtolower(dirname(__DIR__) . DS . basename(__FILE__)) . "):</strong><br />";
	if (is_array($value))
	{
		echo "<pre>";
		print_r($value);
		echo "</pre>";
	}
	else
	{
		var_dump($value);
	}
	echo "<hr>";
}

/* Get something from the global configuration, for example: 
	- getConfig("database") (returns an array)
	- getConfig("database.username") (returns just the username from the array)
*/
function getConfig(string $path)
{
	if ($path)
	{
		$path = explode(".", $path);
		$config = $GLOBALS["CONFIG"];

		foreach($path as $p)
		{
			if(isset($config[$p]))
			{
				$config = $config[$p];
			}
		}
		return $config;
	}
	else
	{
		throw new \Exception("Config path was not found!");
	}
}

function getCookie(string $cookieName) : string
{
	if(isset($_COOKIE[$cookieName]))
	{
		$value = $_COOKIE[$cookieName];
		$value = \Framework\CryptXOR($value);

		return $value;
	}
	else
	{
		return "";
	}
}

function createCookie(string $name, string $value, int $expires)
{
	$value = \Framework\CryptXOR($value);
	setcookie($name, $value, $expires);
}

/*
Encrypt/Decrypt a string using XOR-encoding.
Key is stored in configuration file.
*/
function CryptXOR(string $text) : string
{
	$key = \Framework\getConfig("xorCryptKey");

    $key = array_map('ord', str_split($key)); // text2ascii
    $text = array_map('ord', str_split($text)); // text2ascii
    $keysize = count($key);
	$input_size = count($text);
	
    $cipher = "";
	for ($i = 0; $i < $input_size; $i++)
	{
		$cipher .= chr($text[$i] ^ $key[$i % $keysize]);
	}
        
    return $cipher;
}


/*
Automatically find class files when a new object is instanciated;
See config/paths.php for a list of directories that is searched.

Class files should ALWAYS:
	- Start with lowercase
	- CamelCased

Example:
	- appController.php
	- templateEngine.php
*/
function autoload($class) 
{
	// Determine class name when using namespaces
	$className = str_replace("\\", DS, $class . ".php");
	$className =  array_map("lcfirst", explode(DS, $className));

	// Hack for Vendor libraries
	if ($className[0] == "framework")
	{
		unset($className[0]);
	}
	$className = implode(DS, $className);
	$classFile = ROOT . DS . $className;

	// If class found directly, include
	if (file_exists($classFile))
	{
		require_once $classFile;
	}
	else // Look in AUTOLOAD_CLASSES (see config/paths.php) for Controllers, etc.
	{
		foreach (unserialize(AUTOLOAD_CLASSES) as $classPath)
		{
			$classPath = str_replace("\\", DS, $classPath);
			$className = explode(DS, lcfirst($className))[0];
			$classFile = $classPath . $className;

			if (file_exists($classFile))
			{
				require_once $classFile;
				break;
			}
		}

		// Load Vendor libraries
		$className =  array_map("ucfirst", explode(DS, $className));
		$className = implode(DS, $className);
		$className = str_replace(".php", "", $className);

		$vendorLibs = [
			"smarty" => TEMPLATING_ENGINE . $className . ".class.php",
			"phpmailer" => PHP_MAILER . $className . ".php"
		];

		foreach ($vendorLibs as $lib)
		{
			if (file_exists($lib))
			{
				require_once $lib;
			}
		}
	}
}

// Register the autoloader
spl_autoload_register('\Framework\autoload');
?>