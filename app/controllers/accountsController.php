<?php
namespace App\Controllers;

use App\Models;
use Framework\EmailEngine;

class AccountsController extends AppController
{
    protected $model = NULL;
	protected $emailEngine = NULL;

	public function __construct(string $action = "", array $params)
	{
		parent::__construct($action, $params);

		$this->model = new Models\AccountsModel();
		$this->action = $action;
		
		$this->emailEngine = new EmailEngine(true);
    }
    
    public function accountOverview(array $params)
    {
        $this->setLayout("authenticated");
        
        $username = \Framework\CryptXOR($_SESSION["username"]);
        $accounts = $this->model->getAccounts((int) $_SESSION["userId"]);
        // $accounts = getAccount(int $userid);

        $this->view->assign("username", $username);
        $this->view->assign("accounts", $accounts);

        $this->view->assign("title", "Accounts");
        $this->view->display("accounts/accountsOverview.tpl");
    }

    public function addAccount(array $params)
    {
		$this->view->assign("title", "Add Account");
        $this->view->assign("POST_URL", $this->getUrlSelf());
        
        $this->setLayout("authenticated");
        $username = $this->model->getUsername($_SESSION["email"]);

        $this->view->assign("username", $username);

        // Check registration form stuff
        if (!empty($_POST))
        {
            \Framework\debug($_SESSION);
            \Framework\debug($_POST);
            // exit();
            $userid = $_SESSION["userId"];
            $accountname = $_POST["accountname"];
            $accounttype = $_POST["accounttype"];
            $amount = doubleval($_POST["amount"]);

            $this->model->createAccount($userid, $accountname, $accounttype, $amount);

        }

		$this->view->display("accounts/addAccounts.tpl");

    }
}
?>