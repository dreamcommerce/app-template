<?php
// region i18n
if (empty($_GET['locale'])) {
    die();
}
chdir(__DIR__);
setlocale(LC_ALL, basename($_GET['locale']));
//endregion

//region php configuration
set_time_limit(0);
// endregion

try {
    $config = require 'src/bootstrap.php';

    $app = new App($config);
    $app->bootstrap();
} catch (\Exception $ex) {
    @header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);

    if ($app instanceof App) {
        $app->handleException($ex);
    } else {
        if (class_exists("\\DreamCommerce\\ShopAppstoreLib\\Logger")) {
            $logger = new \DreamCommerce\ShopAppstoreLib\Logger;
            $logger->error('Message: ' . $ex->getMessage() . '; code: ' . $ex->getCode() . '; stack trace: ' . $ex->getTraceAsString());
        } else {
            die($ex->getMessage());
        }
    }
}
