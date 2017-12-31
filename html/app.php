<?php

use Symfony\Component\Debug\Debug;
use Symfony\Component\ClassLoader\ApcClassLoader;
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__.'/../app/bootstrap.php.cache';

if (isset($_SERVER['SYMFONY_DEBUG']) ? (bool) $_SERVER['SYMFONY_DEBUG'] : false) {
    // Disable OpCache
    ini_set('opcache.enable', 0);
    umask(0000);
    Debug::enable();
}

require_once __DIR__.'/../app/AppKernel.php';

$kernel = new AppKernel(isset($_SERVER['SYMFONY_ENV']) ? $_SERVER['SYMFONY_ENV'] : 'dev', isset($_SERVER['SYMFONY_DEBUG']) ? (bool) $_SERVER['SYMFONY_DEBUG'] : false);
$kernel->loadClassCache();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
