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
            call_user_func($this->throwUsageError, "Missing task: $taskName");
        }
        return $this->tasks[$taskName];
    }

    public function taskExist($taskName) {
        if (!isset($this->tasks[$taskName])) {
            return false;
        }
        return true;
    }

    public function getTasks() {
        return $this->tasks;
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
        if ($function && !is_callable($function)) {
            $args[] = $function;
            $function = null;
        }
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
