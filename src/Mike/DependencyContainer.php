<?php

namespace Mike;

class DependencyContainer {

    static private $instance;

    static public function getTaskLoader() {
        return self::$instance->jiggle->taskLoader;
    }

    static public function getTaskRunner() {
        return self::$instance->jiggle->taskRunner;
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
        'no-color' => array(
            'short'       => 'n',
            'description' => 'Do not colorize output messages',
            'argument'    => null,
        ),
    );

    public function __construct() {
        $jiggle = new \Jiggle;

        $jiggle->disableColors = false;

        $jiggle->runApplication = function($argumentReader) use($jiggle) {
            return function() use($argumentReader, $jiggle) {
                try {
                    // catch usage errors during creation and run phase
                    $jiggle->replace('disableColors', $argumentReader->isFlagSet('no-color'));
                    $app = $jiggle->create('Mike\Application');
                    $app->run();
                } catch (UsageError $e) {
                    $jiggle->output->errorMessage($e->getMessage());
                    $jiggle->process->quit(1);
                }
            };
        };
        $jiggle->taskFileFinder         = $jiggle->singleton('Mike\TaskFileFinder');
        $jiggle->taskLoader             = $jiggle->singleton('Mike\TaskLoader');
        $jiggle->taskRunner             = $jiggle->singleton('Mike\TaskRunner');
        $jiggle->output                 = $jiggle->singleton('Mike\Output');
        $jiggle->colorizer              = $jiggle->singleton('Mike\Colorizer');
        $jiggle->process                = $jiggle->singleton('Mike\Process');
        $jiggle->interactiveParamReader = $jiggle->singleton('Mike\InteractiveParamReader');
        $jiggle->argumentReader         = $jiggle->singleton('Mike\ArgumentReader');
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
