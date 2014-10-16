<?php
ini_set('display_errors', 'on');

// region autoloaders + sources fetching
require 'vendor/autoload.php';

// copy it from sdk if you need to alter
require 'vendor/dreamcommerce/shop-appstore-lib/examples/BillingSystem/App.php';
//endregion

date_default_timezone_set('Europe/Warsaw');

if(empty($_POST['shop_url'])){
    die;
}

$billingSystem = new BillingSystem\App($_POST['shop_url']);
$billingSystem->dispatch();