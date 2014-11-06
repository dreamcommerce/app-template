<?php
/**
 * Created by PhpStorm.
 * User: eRIZ
 * Date: 2014-11-06
 * Time: 17:04
 */

namespace Controllers;


class AbstractController implements ArrayAccess {

    /**
     * @var App
     */
    protected $app;

    /**
     * @param App $app
     */
    public function __construct(App $app){
        $this->app = $app;
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
            require __DIR__ . '/../../views/' . basename($tpl, '.php') . '.php';
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