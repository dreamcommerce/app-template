<?php
ini_set('display_errors', 'on');

// region autoloaders + sources fetching
set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__.'/src');
spl_autoload_register();
// uncomment if you're using composer
//require 'vendor/autoload.php';

require 'src/App.php';
//endregion

date_default_timezone_set('Europe/Warsaw');

if(empty($_GET['locale'])) {
    die();
}

try {

    $app = new App();
    $app->bootstrap();

}catch (Exception $ex){

    if($app instanceof App){
        $app->handleException($ex);
    }else{
        die($ex->getMessage());
    }

}