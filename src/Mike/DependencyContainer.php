<?php

namespace Mike;

class DependencyContainer {

    static private $instance;

    static public function getTaskLoader() {
        return self::$instance->jiggle->taskLoader;
    }

    private $jiggle;

    public function __construct() {
        $this->jiggle = new \Jiggle;
        $this->jiggle->main           = $this->jiggle->createFactory('Mike\Main');
        $this->jiggle->taskFileFinder = $this->jiggle->createFactory('Mike\TaskFileFinder');
        $this->jiggle->process        = $this->jiggle->createFactory('Mike\Process');
        $this->jiggle->taskLoader     = $this->jiggle->createFactory('Mike\TaskLoader');
        $this->jiggle->argumentReader = $this->jiggle->createFactory('Mike\ArgumentReader');
        $this->jiggle->taskRunner     = $this->jiggle->createFactory('Mike\TaskRunner');
        $this->jiggle->interactiveParamReader =
            $this->jiggle->createFactory('Mike\InteractiveParamReader');

        self::$instance = $this;
    }

    public function getDependencies() {
        return $this->jiggle;
    }

}
