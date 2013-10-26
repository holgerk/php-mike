<?php

namespace Mike;

class TaskRunner {

    public function __construct() {
    }

    public function run($task) {
        $task->run();
    }

}
