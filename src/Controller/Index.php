<?php
namespace Controller;

use DreamCommerce\ShopAppstoreLib\Resource\Category;

class Index extends ControllerAbstract
{
    public function indexAction()
    {
        $client = $this->app->getClient();
        $categoriesResource = new Category($client);
        $this['categories'] = $categoriesResource->get();
    }
}
