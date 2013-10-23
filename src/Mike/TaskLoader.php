<?php

namespace Mike;

class TaskLoader {

    static private $instance;

    static public function current() {
        return self::$instance;
    }

    private $tasks = array();
    private $lastDescription = '';

    public function __construct() {
        self::$instance = $this;
    }

    public function loadFile($taskFile) {
        require $taskFile;
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

    }

    // ==============================================================================


    private function getTaskDescription() {
        $description = $this->lastDescription;
        $this->lastDescription = '';
        return $description;
    }

}
