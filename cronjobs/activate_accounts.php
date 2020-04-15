<?php
namespace App\Models;

// Bootstrap the application
@include_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . "bootstrap.php";

// Include Appmodel for database
class AccountsCron extends AppModel 
{
    public function __construct()
	{
        parent::__construct();
    }

    public function activateAllAccounts() {
        $dbHandle = $this->database->prepare("UPDATE users SET activation_status = 1");
        $dbHandle->execute();
        $dbHandle->close();
    }

    public function deactivateAllAccounts() {
        $dbHandle = $this->database->prepare("UPDATE users SET activation_status = 0");
        $dbHandle->execute();
        $dbHandle->close();
    }
}

$cronjob = new AccountsCron();
$cronjob->activateAllAccounts();
?>