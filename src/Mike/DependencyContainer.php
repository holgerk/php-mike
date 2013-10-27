<?php

namespace Mike;

class DependencyContainer {

    static private $instance;

    static public function getTaskLoader() {
        return self::$instance->jiggle->taskLoader;
    }

    private $jiggle;

    public function __construct() {
        $jiggle = new \Jiggle;

        $jiggle->main                   = $jiggle->createFactory('Mike\Main');
        $jiggle->taskFileFinder         = $jiggle->createFactory('Mike\TaskFileFinder');
        $jiggle->process                = $jiggle->createFactory('Mike\Process');
        $jiggle->taskLoader             = $jiggle->createFactory('Mike\TaskLoader');
        $jiggle->argumentReader         = $jiggle->createFactory('Mike\ArgumentReader');
        $jiggle->taskRunner             = $jiggle->createFactory('Mike\TaskRunner');
        $jiggle->terminal               = $jiggle->createFactory('Mike\Terminal');
        $jiggle->interactiveParamReader = $jiggle->createFactory('Mike\InteractiveParamReader');
        $jiggle->throwUsageError        = function() {
            return function($message) {
                throw new UsageError($message);
            };
        };

        $this->jiggle = $jiggle;
        self::$instance = $this;
    }

    public function getDependencies() {
        return $this->jiggle;
    }

}
