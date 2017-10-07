<?php
// valid request?
if (empty($_POST['shop_url']) || empty($_POST['action'])) {
    die;
}
chdir(__DIR__);
try {
    $config = require_once 'src/bootstrap.php';

    $billingSystem = new BillingSystem\App($_POST['shop_url'], $config);
    $billingSystem->dispatch();
} catch (\Exception $ex) {
    @header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);

    if (class_exists("\\DreamCommerce\\ShopAppstoreLib\\Logger")) {
        $logger = new \DreamCommerce\ShopAppstoreLib\Logger;
        $logger->error('Message: ' . $ex->getMessage() . '; code: ' . $ex->getCode() . '; stack trace: ' . $ex->getTraceAsString());
    } else {
        die($ex->getMessage());
    }
}
