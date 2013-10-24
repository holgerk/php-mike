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

        self::$instance = $this;
    }

    public function getDependencies() {
        return $this->jiggle;
    }

}
