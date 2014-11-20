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
     * @param \App $app
     */
    public function __construct(\App $app){
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

        if(!$this->app->config['useRewrite']){

            $params = array();
            parse_str($_SERVER['QUERY_STRING'], $params);
            $params['q'] = $url;

            $query = http_build_query($params);

            return $url.'?'.$query;

        }else{
            return $url.'?'.$_SERVER['QUERY_STRING'];
        }
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

        // separate scopes
        $render = function () use ($tpl, $vars) {
            extract($vars);
            require __DIR__ . '/../../view/' . basename($tpl, '.php') . '.php';
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