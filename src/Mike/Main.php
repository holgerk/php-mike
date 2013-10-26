<?php

namespace Mike;

class Main {

    public function __construct(
            $taskFileFinder,
            $taskLoader,
            $process,
            $argumentReader,
            $taskRunner) {
        $this->taskFileFinder = $taskFileFinder;
        $this->taskLoader     = $taskLoader;
        $this->process        = $process;
        $this->argumentReader = $argumentReader;
        $this->taskRunner     = $taskRunner;
    }

    public function run() {
        $taskFile = $this->taskFileFinder->find();
        if (!$taskFile) {
            $this->process->quit();
            return;
        }
        $this->taskLoader->loadFile($taskFile);
        foreach ($this->argumentReader->getTasks() as $taskName) {
            $taskParams = $this->argumentReader->getTaskArgs($taskName);
            $this->taskRunner->run($taskName, $taskParams);
        }
    }

}
