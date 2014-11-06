<?php
require 'src/bootstrap.php';

// valid request?
if(empty($_POST['shop_url']) || empty($_POST['action'])){
    die;
}

try{
    $billingSystem = new BillingSystem\App($_POST['shop_url']);
    $billingSystem->dispatch();
}catch(\Exception $ex){
    file_put_contents('log.log', $ex->getMessage().PHP_EOL, FILE_APPEND);
}