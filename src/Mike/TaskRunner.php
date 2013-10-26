<?php

namespace Mike;

class TaskRunner {

    public function __construct($taskLoader) {
        $this->taskLoader = $taskLoader;
    }

    public function run($taskName, $args = array()) {
        $task = $this->taskLoader->getTask($taskName);
        foreach ($task->getDependencies() as $dependency) {
            $this->run($dependency, $args);
        }

        $task->run($this->fetchTaskParams($task, $args));
    }

    private function fetchTaskParams($task, $callArgs) {
        $params = array();
        foreach ($task->getParams() as $param) {
            if (isset($callArgs[$param->getName()])) {
                $params[] = $callArgs[$param->getName()];
            } else if ($param->isOptional()) {
                $params[] = $param->getDefaultValue();
            } else {
                throw new \Exception("missing task param: " . $param->getName());
            }
        }
        return $params;
    }

}
