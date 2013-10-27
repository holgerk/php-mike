<?php

namespace Mike;

class TaskRunner {

    public function __construct($taskLoader, $interactiveParamReader) {
        $this->taskLoader = $taskLoader;
        $this->interactiveParamReader = $interactiveParamReader;
        $this->taskStack = array();
    }

    public function run($taskName, $args = array()) {
        if (in_array($taskName, $this->taskStack)) {
            $chain = implode(' > ', $this->taskStack) . " > $taskName";
            throw new \Exception("circular dependency: $chain!");
        }
        $this->taskStack[] = $taskName;

        $task = $this->taskLoader->getTask($taskName);
        if (!$task) {
            throw new \Exception("missing task: $taskName!");
        }
        foreach ($task->getDependencies() as $dependency) {
            $this->run($dependency, $args);
        }

        $task->run($this->fetchTaskParams($task, $args));
        array_pop($this->taskStack);
    }

    private function fetchTaskParams($task, $callArgs) {
        $params = array();
        foreach ($task->getParams() as $param) {
            $paramName = $param->getName();
            if (isset($callArgs[$paramName])) {
                $params[] = $callArgs[$paramName];
            } else if ($param->isOptional()) {
                $params[] = $param->getDefaultValue();
            } else {
                $params[] = $this->interactiveParamReader->read($paramName);
            }
        }
        return $params;
    }

}
