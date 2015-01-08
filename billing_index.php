<?php
// valid request?
if(empty($_POST['shop_url']) || empty($_POST['action'])){
    die;
}

try{

    $config = require_once 'src/bootstrap.php';

    $billingSystem = new BillingSystem\App($_POST['shop_url'], $config);
    $billingSystem->dispatch();
}catch(\Exception $ex){
    if(class_exists("\\DreamCommerce\\Logger")) {
        \DreamCommerce\Logger::error($ex);
    }else{
        die($ex->getMessage());
    }
}