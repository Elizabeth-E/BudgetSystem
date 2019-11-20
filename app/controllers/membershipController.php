<?php
namespace App\Controllers;

use App\Models;
use Framework\EmailEngine;

class MembershipController extends AppController
{
    protected $model = NULL;
	protected $emailEngine = NULL;

	public function __construct(string $action = "", array $params)
	{
		parent::__construct($action, $params);

		$this->model = new Models\MembershipModel();
		$this->action = $action;
		
		$this->emailEngine = new EmailEngine(true);
    }
    
    public function membershipOverviewDefault(array $params)
    {
        $this->setLayout("default");

        $this->view->assign("title", "Accounts");
        $this->view->display("membership/newMembers.tpl");
    }

    public function membershipOverview(array $params)
    {
        $this->setLayout("authenticated");
        $username = $this->model->getUsername($_SESSION["email"]);

        $this->view->assign("username", $username);

        $this->view->assign("title", "Accounts");
        $this->view->display("membership/overview.tpl");
    }
}
?>