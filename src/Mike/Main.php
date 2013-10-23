<?php

namespace Mike;

class Main {

    public function __construct($taskFileFinder, $taskFileLoader, $process) {
        $this->taskFileFinder = $taskFileFinder;
        $this->taskFileLoader = $taskFileLoader;
        $this->process = $process;
    }

    public function run() {
        $taskFile = $this->taskFileFinder->find();
        if (!$taskFile) {
            $this->process->quit();
            return;
        }
        $this->taskFileLoader->load($taskFile);
    }

}