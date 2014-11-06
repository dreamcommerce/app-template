<?php
/**
 * Created by PhpStorm.
 * User: eRIZ
 * Date: 2014-11-06
 * Time: 17:03
 */

namespace Controllers;


class Index extends AbstractController{

    public function indexAction(){
        $this['categories'] = $this->app->client->categories->get();
    }

} 