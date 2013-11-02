<?php

namespace Mike;

class ArgumentReader {

    public function __construct($process, $commandLineFlags, $throwUsageError) {
        $this->argv = $process->argv();
        $this->argv = array_splice($this->argv, 1);
        $this->commandLineFlags = $commandLineFlags;
        $this->throwUsageError = $throwUsageError;

        $this->taskArgs = array();
        $this->flags = array();
        $this->flagArguments = array();

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

    public function isFlagSet($flagName) {
        if (isset($this->flags[$flagName])) {
            return true;
        }
        return false;
    }

    public function getFlagArgument($flagName) {
        return $this->flagArguments[$flagName];
    }

    private function parseArgv() {
        $flagWithArgument = null;
        foreach ($this->argv as $arg) {
            if ($flagWithArgument) {
                // flag argument
                $this->flagArguments[$flagWithArgument] = $arg;
                $flagWithArgument = null;
            } else if (strlen($arg) && $arg[0] == '-') {
                // flag
                $flagName = $this->registerFlag($arg);
                $flagWithArgument = !is_null($this->commandLineFlags[$flagName]['argument'])
                    ? $flagName
                    : null;
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

    private function registerFlag($flag) {
        $isLongFlag = substr($flag, 0, 2) == '--';
        $flagName = ltrim($flag, '-');
        if (!$isLongFlag) {
            $found = false;
            foreach ($this->commandLineFlags as $longName => $flagDef) {
                if ($flagName == $flagDef['short']) {
                    $flagName = $longName;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $flagName = '';
            }
        }
        if (!isset($this->commandLineFlags[$flagName])) {
            call_user_func($this->throwUsageError, "Invalid option: $flag!");
        }
        $this->flags[$flagName] = true;
        return $flagName;
    }

}
