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
        $username = $this->model->getUsername($_SESSION["email"]);

        $this->view->assign("username", $username);

        $this->view->assign("title", "Accounts");
        $this->view->display("bills/overview.tpl");
    }
}
?>