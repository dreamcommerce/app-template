<?php

// various PHP configuration values
date_default_timezone_set('Europe/Warsaw');
ini_set('display_errors', 'on');

// force utf-8 as primary encoding
if (PHP_VERSION_ID < 50600) {
    mb_internal_encoding('utf-8');
} else {
    ini_set('default_charset', 'utf-8');
}

// internal autoloader
spl_autoload_register(function($class){
    $class = str_replace('\\', '/', $class);
    require 'src/'.$class.'.php';
});

// composer autoloader - patched automatically
//require 'vendor/autoload.php';

$config = require './Config.php';

if(isset($config['debug'])){
    putenv('DREAMCOMMERCE_DEBUG='.$config['debug']);
}

return $config;