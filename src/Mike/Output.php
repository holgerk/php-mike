<?php

namespace Mike;

class Output {

    public function __construct($process, $taskLoader, $colorizer) {
        $this->process = $process;
        $this->taskLoader = $taskLoader;
        $this->colorizer = $colorizer;
    }

    public function errorMessage($message) {
        $clearLine = "\033[2K";
        $redBg = "\033[1;41m";
        $whiteBold = "\033[1;37m";
        $reset = "\033[0m";
        echo "$redBg$whiteBold$clearLine$message\n$reset";
    }

    public function helpMessage() {
        echo "mike [flags] [tasks]\n";
    }

    public function showTasks() {
        $tasks = $this->taskLoader->getTasks();
        $output = '';

        $maxTaskNameLength = array_reduce($tasks, function($max, $task) {
            return max($max, strlen($task->getName()));
        }, 0);

        foreach ($tasks as $task) {
            $output .= sprintf(
                "%-${maxTaskNameLength}s # %s\n",
                $this->colorizer->bold($task->getName()),
                $task->getDescription());
        }
        $this->process->output($output);
    }

}

