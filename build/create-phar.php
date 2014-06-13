<?php

$pharFile = __DIR__ .'/mike.phar';
if (file_exists($pharFile)) {
    unlink($pharFile);
}

$phar = new Phar($pharFile);
$rootDirectory = realpath(__DIR__ . '/..');
$directoryWhitelist = array(
    'bin/',
    'src/',
    'vendor/composer/',
    'vendor/holgerk/jiggle/src/',
    'vendor/autoload', // <- matches autoload.php in vendor directory
);
$phar->buildFromDirectory(
    $rootDirectory,
    '%' . preg_quote($rootDirectory) . '/(' . implode('|', $directoryWhitelist) . ').*\.php%');

$phar->setStub(
    "#!/usr/bin/env php\n" .
    $phar->createDefaultStub('bin/mike.php'));

