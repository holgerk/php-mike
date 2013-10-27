<?php

namespace Mike;

class Main {

    public function __construct(
            $taskFileFinder,
            $taskLoader,
            $process,
            $argumentReader,
            $taskRunner,
            $terminal) {
        $this->taskFileFinder = $taskFileFinder;
        $this->taskLoader     = $taskLoader;
        $this->process        = $process;
        $this->argumentReader = $argumentReader;
        $this->taskRunner     = $taskRunner;
        $this->terminal       = $terminal;
    }

    public function run() {
        try {
            $taskFile = $this->taskFileFinder->find();
            $this->taskLoader->loadFile($taskFile);
            foreach ($this->argumentReader->getTasks() as $taskName) {
                $taskParams = $this->argumentReader->getTaskArgs($taskName);
                $this->taskRunner->run($taskName, $taskParams);
            }
        } catch (UsageError $e) {
            $this->terminal->errorMessage($e->getMessage());
            $this->process->quit(1);
        }
    }

}
