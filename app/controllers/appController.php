<?php
namespace App\Controllers;

use Framework\TemplateEngine;

abstract class AppController 
{
    protected $view = "";
    protected $action = "";
    protected $params = [];

    // Default layout
    protected $layout = VIEWS . "layouts" . DS . "default" . DS;

    public function __construct(string $action = "", array $params)
    {
        // Set the corresponding View and Action
        $this->action = $action;
        $this->params = $params;
        $this->view = new TemplateEngine(); // Initiate template engine

        $this->view->assign("layout", $this->layout);
        $this->view->assign("www", BASE_URL);
    }

    protected function setLayout(string $layout)
    {
        // Change layout
        $this->layout = VIEWS . "layouts" . DS . $layout . DS;
        $this->view->assign("layout", $this->layout);
    }


    protected function getUrlSelf() : string
    {
        $url = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        return $url;
    }

    public function dispatch()
    {
        if ( ! empty($this->action))
        {
            $this->{$this->action}($this->params); // Dispatch to provided action
        }
    }
}
?>