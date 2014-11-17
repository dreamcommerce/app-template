<?php
require 'src/bootstrap.php';

// region i18n
if(empty($_GET['locale'])) {
    die();
}

setlocale(LC_ALL, basename($_GET['locale']));
bindtextdomain('messages', './i18n');
bind_textdomain_codeset('messages', 'UTF-8');
textdomain('messages');
//endregion

//region php configuration
set_time_limit(0);
session_start();
// endregion

try {

    $config = require_once 'src/Config.php';

    $app = new App($config);
    $app->bootstrap();

}catch (Exception $ex){

    $message = $ex->getMessage();
    require 'views/exception.php';

}