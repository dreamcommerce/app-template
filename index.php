<?php
// region i18n
if(empty($_GET['locale'])) {
    die();
}

setlocale(LC_ALL, basename($_GET['locale']));
//endregion

//region php configuration
set_time_limit(0);
// endregion

try {

    $config = require 'src/bootstrap.php';

    $app = new App($config);
    $app->bootstrap();

}catch (\Exception $ex){

    if($app instanceof App){
        $app->handleException($ex);
    }else{
        if(class_exists("\\DreamCommerce\\Logger")) {
            \DreamCommerce\Logger::error($ex);
        }else{
            die($ex->getMessage());
        }
    }

}