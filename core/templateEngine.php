<?php
namespace Framework;

// Set up the Smarty Templating Engine
class TemplateEngine extends \Smarty
{
    public function __construct()
    {
        parent::__construct();

        $this->setTemplateDir(APP . "views" . DS);
        $this->setCompileDir(TMP . "templates_compiled" . DS);
        $this->setCacheDir(TMP . "templates_cache" . DS);
    }
}
?>