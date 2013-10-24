<?php

namespace Mike;

class ArgumentReader {

    public function __construct($process) {
        $this->process = $process;
        $this->argv = array_splice($process->argv(), 1);
        $this->taskDataList = array();
        $this->position = 0;
        $this->parseArgv();
    }

    public function nextTaskData() {
        if (!isset($this->taskDataList[$this->position])) {
            return null;
        }
        $taskData = $this->taskDataList[$this->position];
        $this->position++;
        return $taskData;
    }

    private function parseArgv() {
        foreach ($this->argv as $arg) {
            if (strlen($arg) && $arg[0] == '-') {
                // flag
            } else if (strpos($arg, '=') !== false) {
                // param
                list($name, $value) = explode('=', $arg, 2);
                $taskData[1][$name] = $value;
            } else {
                // task
                $this->taskDataList[] = array($arg, array());
                $taskData = &$this->taskDataList[count($this->taskDataList) - 1];
            }
        }
    }

}
