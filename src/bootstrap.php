<?php
// force utf-8 as primary encoding
if (PHP_VERSION_ID < 50600) {
    mb_internal_encoding('utf-8');
} else {
    ini_set('default_charset', 'utf-8');
}

// internal autoloader
spl_autoload_register(function($class){
    $class = str_replace('\\', '/', $class);
    if (file_exists('src/'.$class.'.php')) {
        require 'src/'.$class.'.php';
    }
});

// composer autoloader - patched automatically
//require 'vendor/autoload.php';

$config = require __DIR__. '/Config.php';

// various PHP configuration values
date_default_timezone_set($config['timezone']);
ini_set('display_errors', $config['php']['display_errors']);

// check debug mode options
$debug = false;
if(isset($config['debug'])){
    if($config['debug']){
        $debug = true;
    }
}
if(getenv('DREAMCOMMERCE_DEBUG')){
    $debug = true;
}
define("DREAMCOMMERCE_DEBUG", $debug);

// log errors
$logFile = "php://stdout";
if(isset($config['logFile'])){
    if($config['logFile']){
        $logFile = $config['logFile'];
    }else{
        $config['logFile'] = false;
    }
}
define("DREAMCOMMERCE_LOG_FILE", $logFile);

return $config;
