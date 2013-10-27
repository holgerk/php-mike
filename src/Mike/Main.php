<?php

namespace Mike;

class Main {

    public function __construct(
            $taskFileFinder,
            $taskLoader,
            $process,
            $argumentReader,
            $taskRunner,
            $terminal,
            $throwUsageError) {
        $this->taskFileFinder  = $taskFileFinder;
        $this->taskLoader      = $taskLoader;
        $this->process         = $process;
        $this->argumentReader  = $argumentReader;
        $this->taskRunner      = $taskRunner;
        $this->terminal        = $terminal;
        $this->throwUsageError = $throwUsageError;
    }

    public function run() {
        try {
            $taskFile = $this->taskFileFinder->find();
            $this->taskLoader->loadFile($taskFile);
            $taskNames = $this->argumentReader->getTasks();
            if (count($taskNames) == 0 && $this->taskLoader->taskExist('default')) {
                $taskNames[] = 'default';
            }
            if (count($taskNames) == 0) {
                call_user_func($this->throwUsageError, 'No task given!');
            }
            foreach ($taskNames as $taskName) {
                $taskParams = $this->argumentReader->getTaskArgs($taskName);
                $this->taskRunner->run($taskName, $taskParams);
            }
        } catch (UsageError $e) {
            $this->terminal->errorMessage($e->getMessage());
            $this->process->quit(1);
        }
    }

}
