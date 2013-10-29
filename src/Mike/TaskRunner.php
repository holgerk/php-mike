<?php

namespace Mike;

class TaskRunner {

    public function __construct($taskLoader, $interactiveParamReader, $throwUsageError) {
        $this->taskLoader = $taskLoader;
        $this->interactiveParamReader = $interactiveParamReader;
        $this->throwUsageError = $throwUsageError;
        $this->taskStack = array();
        $this->tasksThatWasRun = array();
    }

    public function run($taskName, $args = array()) {
        $isDependencyFromOtherTask = (count($this->taskStack) > 0);
        if ($isDependencyFromOtherTask && in_array($taskName, $this->tasksThatWasRun)) {
            return;
        }

        if (in_array($taskName, $this->taskStack)) {
            $chain = implode(' > ', $this->taskStack) . " > $taskName";
            call_user_func_array($this->throwUsageError, array("Circular dependencies: $chain!"));
        }
        $this->taskStack[] = $taskName;

        $task = $this->taskLoader->getTask($taskName);
        foreach ($task->getDependencies() as $dependency) {
            $this->run($dependency, $args);
        }

        $task->run($this->fetchTaskParams($task, $args));
        array_pop($this->taskStack);
        $this->tasksThatWasRun[] = $taskName;
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
