<?php

require_once __DIR__ . '/../vendor/autoload.php';

$jiggle = new Jiggle;

$jiggle->main           = $jiggle->createFactory('Mike\Main');
$jiggle->taskFileFinder = $jiggle->createFactory('Mike\TaskFileFinder');
$jiggle->process        = $jiggle->createFactory('Mike\Process');
$jiggle->taskLoader = $jiggle->createFactory('Mike\TaskLoader');

return $jiggle;
