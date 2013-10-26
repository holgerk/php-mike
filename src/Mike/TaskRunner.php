<?php

namespace Mike;

class TaskRunner {

    public function __construct($taskLoader) {
        $this->taskLoader = $taskLoader;
    }

    public function run($taskName) {
        $task = $this->taskLoader->getTask($taskName);
        foreach ($task->getDependencies() as $dependency) {
            $this->run($dependency);
        }
        $task->run();
    }

}
