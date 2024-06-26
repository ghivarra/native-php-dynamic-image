<?php

// Check PHP versions
$acceptedPHPVersion = '7.4';

if (PHP_VERSION < $acceptedPHPVersion)
{
    header('HTTP/1.1 503 Service Unavailable.', true, 503);
    echo "Your PHP Version must be at least {$acceptedPHPVersion} or higher.";
    exit();
}

// Set path constants
define('PUBLICPATH', __DIR__ . DIRECTORY_SEPARATOR);
define('ROOTPATH', realpath(PUBLICPATH . '..' . DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR);
define('APPPATH', realpath(ROOTPATH . 'app') . DIRECTORY_SEPARATOR);
define('RESOURCEPATH', realpath(ROOTPATH . 'resources') . DIRECTORY_SEPARATOR);

// load ../app/Boot.php
require_once APPPATH . 'Boot.php';
$boot = new App\Boot();

// away we go...
exit($boot->run());