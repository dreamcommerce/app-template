<?php
ini_set('display_errors', 'on');

// region autoloaders + sources fetching
require 'vendor/autoload.php';
$loader = new Riimu\Kit\ClassLoader\ClassLoader();
$loader->addPrefixPath('src/BillingSystem', 'BillingSystem');
$loader->register();

//endregion

date_default_timezone_set('Europe/Warsaw');

if(empty($_POST['shop_url'])){
    die;
}

$billingSystem = new BillingSystem\App($_POST['shop_url']);
$billingSystem->dispatch();