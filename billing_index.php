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
    // todo fix the log path
    file_put_contents('log.log', $ex->getMessage().PHP_EOL, FILE_APPEND);
}