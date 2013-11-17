<?php

namespace Mike;

class TaskRunner {

    public function __construct(
            $taskLoader,
            $interactiveParamReader,
            $throwUsageError,
            $argumentReader) {
        $this->taskLoader = $taskLoader;
        $this->interactiveParamReader = $interactiveParamReader;
        $this->throwUsageError = $throwUsageError;
        $this->argumentReader = $argumentReader;

        $this->registeredParams = new \Jiggle;
        $this->registeredParams->resolver(array($this, 'resolveDepencyOfRegisteredParam'));

        $this->taskStack = array();
        $this->tasksThatWasRun = array();
    }

    public function param($paramName, $paramValue) {
        $this->registeredParams->$paramName = $paramValue;
    }

    public function resolveDepencyOfRegisteredParam($paramName) {
        // try to fetch from command line
        $taskName = $this->getCurrentTaskName();
        if ($taskName) {
            $commandLineArgs = $this->argumentReader->getTaskArgs($taskName);
            if (array_key_exists($paramName, $commandLineArgs)) {
                return $commandLineArgs[$paramName];
            }
        }
        return $this->interactiveParamReader->read($paramName);
    }

    public function run($taskName, $args = array()) {
        $isDependencyFromOtherTask = (count($this->taskStack) > 0);
        if ($isDependencyFromOtherTask && in_array($taskName, $this->tasksThatWasRun)) {
            return;
        }

        if (in_array($taskName, $this->taskStack)) {
            $chain = implode(' > ', $this->taskStack) . " > $taskName";
            call_user_func($this->throwUsageError, "Circular dependencies: $chain!");
        }
        $this->taskStack[] = $taskName;

        $task = $this->taskLoader->getTask($taskName);
        foreach ($task->getDependencies() as $dependency) {
            $this->run($dependency, $args);
        }

        $result = $task->run($this->fetchTaskParams($task, $args));

        array_pop($this->taskStack);
        $this->tasksThatWasRun[] = $taskName;

        return $result;
    }

    private function getCurrentTaskName() {
        if (count($this->taskStack) == 0) {
            return null;
        }
        return $this->taskStack[count($this->taskStack) - 1];
    }

    private function fetchTaskParams($task, $callArgs) {
        $params = array();
        foreach ($task->getParams() as $param) {
            $paramName = $param->getName();

            // params provided via command line or direct call
            if (isset($callArgs[$paramName])) {
                $params[] = $callArgs[$paramName];

            // param value registered by param call
            } else if ($this->isRegisteredParam($paramName)) {
                $params[] = $this->resolveRegisteredParam($paramName);

            } else if ($param->isOptional()) {
                $params[] = $param->getDefaultValue();
            } else {
                $params[] = $this->interactiveParamReader->read($paramName);
            }
        }
        return $params;
    }

    private function isRegisteredParam($paramName) {
        return isset($this->registeredParams->$paramName);
    }

    private function resolveRegisteredParam($paramName) {
        return $this->registeredParams->$paramName;
    }

}
