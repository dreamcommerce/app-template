<?php
namespace Controller;


class ControllerAbstract implements \ArrayAccess {

    /**
     * @var App
     */
    protected $app;

    /**
     * @var array
     */
    protected $viewVars = array();

    /**
     * @var array request params
     */
    protected $params = array();

    /**
     * @param \App $app
     * @param array $params
     */
    public function __construct(\App $app, $params = array()){
        $this->params = $params;
        $this->app = $app;
    }

    /**
     * perform internal redirect (allows keeping all parameters within URL)
     * @param string $url
     */
    public function redirect($url){

        header('Location: '.$this->getUrl($url));
        exit;

    }

    /**
     * get url to redirect to
     * @param string $url
     * @return string
     */
    public function getUrl($url){
        return \App::getUrl($url);
    }


    // region templating

    /**
     * render application view
     * @param $tpl
     */
    public function render($tpl)
    {

        static $called = false;

        if ($called) {
            return;
        }

        $called = true;

        $vars = $this->viewVars;
        $vars["_locale"] = $this->app->getLocale();

        // separate scopes
        $render = function () use ($tpl, $vars) {
            $template = explode("/", $tpl, 2);
            if(count($template) == 2){
                if($template[0] == "." or $template[0] == ".."){
                    return;
                }
                $__t = $template[0] . "/". basename($template[1], '.php');
            }elseif(count($template) == 1){
                $__t = basename($template[0], '.php');
            }else{
                return;
            }
            unset($template, $tpl);
            unset($vars['__t']);
            extract($vars);
            require __DIR__ . '/../../view/' . $__t . '.php';
        };

        $render();
    }


    public function offsetExists($offset)
    {
        return isset($this->viewVars[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->viewVars[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->viewVars[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->viewVars[$offset]);
    }

    // endregion

} 
