<?php
namespace Controllers;

class Index extends AbstractController{

    public function indexAction(){
        $this['categories'] = $this->app->client->categories->get();
    }

} 