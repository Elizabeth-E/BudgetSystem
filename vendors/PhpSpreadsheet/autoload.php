<?php
// Set path to project core "C:/xampp/htdocs/budget_system/app/vendors/", but remove "app/"
$vendor_path = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendors' . DIRECTORY_SEPARATOR;
$vendor_path = explode('\\', $vendor_path);
unset($vendor_path[count($vendor_path) -3]);
$vendor_path = implode('/', $vendor_path);

define("VENDOR_PATH", $vendor_path);

spl_autoload_register(function ($class_name) {
	$preg_match = preg_match('/^PhpOffice\\\PhpSpreadsheet\\\/', $class_name);

	if (1 === $preg_match) {
		$class_name = preg_replace('/\\\/', '/', $class_name);
		$class_name = preg_replace('/^PhpOffice\\/PhpSpreadsheet\\//', '', $class_name);
		require_once(VENDOR_PATH . 'PhpSpreadsheet/src/PhpSpreadsheet/' . $class_name . '.php');
	}
});