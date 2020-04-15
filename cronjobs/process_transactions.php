<?php
namespace App\Models;

// Bootstrap the application
@include_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . "bootstrap.php";

// Include Appmodel for database
class TransactionsCron extends AppModel 
{
    public function __construct()
	{
        parent::__construct();
    }

    public function processAllBills() {
        $dbHandle = $this->database->prepare("UPDATE bills SET paid = 1");
        $dbHandle->execute();
        $dbHandle->close();
    }

    public function undoAllBills() {
        $dbHandle = $this->database->prepare("UPDATE bills SET paid = 0");
        $dbHandle->execute();
        $dbHandle->close();
    }
}

$cronjob = new TransactionsCron();
$cronjob->processAllBills();
?>