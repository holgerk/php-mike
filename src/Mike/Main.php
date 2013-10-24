<?php

namespace Mike;

class Main {

    public function __construct($taskFileFinder, $taskLoader, $process, $argumentReader) {
        $this->taskFileFinder = $taskFileFinder;
        $this->taskLoader     = $taskLoader;
        $this->process        = $process;
        $this->argumentReader = $argumentReader;
    }

    public function run() {
        $taskFile = $this->taskFileFinder->find();
        if (!$taskFile) {
            $this->process->quit();
            return;
        }
        $this->taskLoader->loadFile($taskFile);
        while (list($taskName, $taskParams) = $this->argumentReader->nextTaskData()) {
        }
    }

}
