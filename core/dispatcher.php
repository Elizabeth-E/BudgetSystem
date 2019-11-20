<?php
namespace Framework;

class Dispatcher
{
    private $url = [];
    private $controller = "";
    private $action = "";
    private $params = [];

    public function __construct(array $url)
    {
        if ( ! empty($url))
        {
            $this->url = $url;
        }

        // If provided set controller name, otherwise use default (homeController)
        if (isset($this->url["controller"]))
        {
            $controllerClass = "App\\Controllers\\" . ucfirst($this->url["controller"]) . "Controller";
            $this->controller = $controllerClass;
        }
        else
        {
            $this->controller = "App\\Controllers\\" . \Framework\getConfig("default.controller") . "Controller";
        }

        // // If provided set action name, otherwise use default (index)
        if (isset($this->url["action"]))
        {
            $this->action = $this->url["action"];
        }
        else
        {
            $this->action = \Framework\getConfig("default.action");
        }

        // If provided set parameters for controller actions
        if (isset($url["params"]))
        {
            $params = explode("/", $this->url["params"]);
            $this->params = $params;
        }

    }

    public function createInstance()
    {
        if (class_exists($this->controller))
        {
            // Every Controller should inherrit from AppController
            $parent = class_parents($this->controller);
            if (in_array("App\\Controllers\\AppController", $parent))
            {
                // Only continue if a corresponding Action is found
                if (method_exists($this->controller, $this->action))
                {
                    return new $this->controller($this->action, $this->params);
                }
                else
                {
                    throw new \Exception("Action " . $this->action . " was not found!");
                }
            }
            else
            {
                throw new \Exception("Action " . $this->controller . " was not found!");
            }
        }
        else
        {
            throw new \Exception($this->controller ." (controller) file does not exist!");
        }
    }
}
?>