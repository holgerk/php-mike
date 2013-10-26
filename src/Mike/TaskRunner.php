<?php

namespace Mike;

class TaskRunner {

    public function __construct($taskLoader) {
        $this->taskLoader = $taskLoader;
    }

    public function run($taskName, $callArgs = array()) {
        $task = $this->taskLoader->getTask($taskName);
        foreach ($task->getDependencies() as $dependency) {
            $this->run($dependency, $callArgs);
        }

        $task->run($this->fetchTaskParams($task, $callArgs));
    }

    private function fetchTaskParams($task, $callArgs) {
        $params = array();
        foreach ($task->getParams() as $param) {
            if (isset($callArgs[$param->getName()])) {
                $params[$param->getName()] = $callArgs[$param->getName()];
            } else if ($param->isOptional()) {
                $params[$param->getName()] = $param->getDefaultValue();
            } else {
                throw new \Exception("missing task param: " . $param->getName());
            }
        }
        return $params;
    }

}
