<?php
namespace Controller;

class Index extends ControllerAbstract{

    public function indexAction(){
        $this['categories'] = $this->app->getClient()->categories->get();
    }

} 