<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;

/**
 * @var ClassLoader $loader
 */
$loader = require __DIR__.'/../vendor/autoload.php';
date_default_timezone_set( 'America/Guatemala' );
AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

return $loader;
