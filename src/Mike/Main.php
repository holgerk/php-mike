<?php

namespace Mike;

class Main {

    public function __construct($taskFileFinder, $taskLoader, $process) {
        $this->taskFileFinder = $taskFileFinder;
        $this->taskLoader = $taskLoader;
        $this->process = $process;
    }

    public function run() {
        $taskFile = $this->taskFileFinder->find();
        if (!$taskFile) {
            $this->process->quit();
            return;
        }
        $this->taskLoader->loadFile($taskFile);
    }

}
