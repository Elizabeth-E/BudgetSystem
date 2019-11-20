<?php
namespace App\Controllers;

use App\Models;

class HomeController extends AppController
{
    protected $model = "";
    protected $params = [];

    public function __construct(string $action = NULL, array $params)
    {
        parent::__construct($action, $params);

        $this->action = $action;
        $this->params = $params;
    }

    public function index(array $params)
    {   
        $rememberMeInput = null;

        $this->view->assign("POST_URL", BASE_URL . "/user/login");

	    // If remmeber me cookie exists, fill input field
        $rememberMeCookie = \Framework\getCookie("remember_login");
        if (!empty($rememberMeCookie))
        {
            $rememberMeInput = $rememberMeCookie;
        }

        $this->view->assign("rememberMeInput", $rememberMeInput);

        $this->view->assign("title", "Welcome");

        $this->view->display("home/index.tpl");
    }
}
?>