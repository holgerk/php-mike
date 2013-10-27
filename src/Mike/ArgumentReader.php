<?php

namespace Mike;

class ArgumentReader {

    public function __construct($process) {
        $this->argv = $process->argv();
        $this->argv = array_splice($this->argv, 1);

        $this->taskArgs = array();
        $this->position = 0;

        $this->parseArgv();
    }

    public function getTasks() {
        return array_keys($this->taskArgs);
    }

    public function getTaskArgs($taskName) {
        if (!isset($this->taskArgs[$taskName])) {
            return array();
        }
        return $this->taskArgs[$taskName];
    }

    private function parseArgv() {
        foreach ($this->argv as $arg) {
            if (strlen($arg) && $arg[0] == '-') {
                // flag
            } else if (strpos($arg, '=') !== false) {
                // param
                list($name, $value) = explode('=', $arg, 2);
                $args[$name] = $value;
            } else {
                // task
                $this->taskArgs[$arg] = array();
                $args = &$this->taskArgs[$arg];
            }
        }
    }

}
