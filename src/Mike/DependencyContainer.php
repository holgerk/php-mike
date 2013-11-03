<?php

namespace Mike;

class DependencyContainer {

    static private $instance;

    static public function getTaskLoader() {
        return self::$instance->jiggle->taskLoader;
    }

    private $jiggle;

    private $commandLineFlags = array(
        'help' => array(
            'short'       => 'h',
            'description' => 'Display help',
            'argument'    => null,
        ),
        'tasks' => array(
            'short'       => 'T',
            'description' => 'Display tasks with description',
            'argument'    => null,
        ),
        'file' => array(
            'short'       => 'f',
            'description' => 'Load the specified task file',
            'argument'    => 'file',
        ),
    );

    public function __construct() {
        $jiggle = new \Jiggle;

        $jiggle->runApplication = function() use($jiggle) {
            return function() use($jiggle) {
                try {
                    // catch usage errors during creation and run phase
                    $app = $jiggle->create('Mike\Application');
                    $app->run();
                } catch (UsageError $e) {
                    $jiggle->output->errorMessage($e->getMessage());
                    $jiggle->process->quit(1);
                }
            };
        };
        $jiggle->taskFileFinder         = $jiggle->createFactory('Mike\TaskFileFinder');
        $jiggle->taskLoader             = $jiggle->createFactory('Mike\TaskLoader');
        $jiggle->taskRunner             = $jiggle->createFactory('Mike\TaskRunner');
        $jiggle->output                 = $jiggle->createFactory('Mike\Output');
        $jiggle->colorizer              = $jiggle->createFactory('Mike\Colorizer');
        $jiggle->process                = $jiggle->createFactory('Mike\Process');
        $jiggle->interactiveParamReader = $jiggle->createFactory('Mike\InteractiveParamReader');
        $jiggle->argumentReader         = $jiggle->createFactory('Mike\ArgumentReader');
        $jiggle->commandLineFlags       = $this->commandLineFlags;
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
