<?php
namespace App\Controllers;

use App\Models;
use Framework\EmailEngine;

class BillsController extends AppController
{
    protected $model = NULL;
	protected $emailEngine = NULL;

	public function __construct(string $action = "", array $params)
	{
		parent::__construct($action, $params);

		$this->model = new Models\BillsModel();
		$this->action = $action;
		
		$this->emailEngine = new EmailEngine(true);
    }
    
    public function billsOverview(array $params)
    {
        $this->setLayout("authenticated");
        $username = \Framework\CryptXOR($_SESSION["username"]);
        $bills = $this->model->getBills($username);
        $categories = $this->model->getBillCategories();
        $accounts = $this->model->getAccounts((int) $_SESSION["userId"]);
        // die('adfsadfsadfsad');

        if (!empty($_POST))
		{
            $name = $_POST["name"];
            $amount = $_POST["amount"];
            $date = $_POST["date"];
            $frequency = $_POST["frequency"];
            $accountId = $_POST["accountId"];
            $billCatId = $_POST["billCatId"];

            // \Framework\debug($_POST);
            // exit();
    
            $this->model->addBill($name, $amount, $date, $frequency, $accountId, $billCatId);
            // header("Refresh:0; url=" . BASE_URL . "/bills/billsOverview", true, 200); 
		}

        $this->view->assign("username", $username);
        $this->view->assign("bills", $bills);
        $this->view->assign("accounts", $accounts);
        $this->view->assign("categories", $categories);
        $this->view->assign("POST_URL", $this->getUrlSelf());

        $this->view->assign("title", "Accounts");
        $this->view->display("bills/overview.tpl");
    }
}
?>