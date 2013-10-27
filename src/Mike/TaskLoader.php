<?php

namespace Mike;

class TaskLoader {

    private $tasks = array();
    private $lastDescription = '';

    public function __construct($throwUsageError) {
        $this->throwUsageError = $throwUsageError;
    }

    public function loadFile($taskFile) {
        require $taskFile;
    }

    public function getTask($taskName) {
        if (!isset($this->tasks[$taskName])) {
            call_user_func_array($this->throwUsageError, array("Missing task: $taskName"));
        }
        return $this->tasks[$taskName];
    }


    // ==============================================================================
    // task file helper functions called via proxy functions defined in functions.php
    // ------------------------------------------------------------------------------

    public function desc($text) {
        $this->lastDescription = $text;
    }

    // args: $name [, $dep1 [, $depN... ], $function
    public function task() {
        $args = func_get_args();
        $name = array_shift($args);
        $function = array_pop($args);
        $this->tasks[$name] = new Task(array(
            'name'         => $name,
            'description'  => $this->getTaskDescription(),
            'dependencies' => $args,
            'function'     => $function
        ));
        return $this->tasks[$name];
    }

    // ==============================================================================


    private function getTaskDescription() {
        $description = $this->lastDescription;
        $this->lastDescription = '';
        return $description;
    }

}
