<?php
ini_set('display_errors', 'on');

// region autoloaders + sources fetching
require 'vendor/autoload.php';
$loader = new Riimu\Kit\ClassLoader\ClassLoader();
$loader->addPrefixPath('src/XmlImporter', 'XmlImporter');
$loader->addPrefixPath('src/BillingSystem', 'BillingSystem');
$loader->register();

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