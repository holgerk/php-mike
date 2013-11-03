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

        $paramStrings = array();
        foreach ($tasks as $task) {
            $paramStrings[$task->getName()] = $this->getParamString($task->getRequiredParams());
        }

        $maxTaskSignatureLength = array_reduce($tasks, function($max, $task) use($paramStrings) {
            $taskName = $task->getName();
            $taskParamString = $paramStrings[$taskName];
            $taskSignature = trim(sprintf('%s %s', $taskName, $taskParamString));
            return max($max, strlen($taskSignature));
        }, 0);

        foreach ($tasks as $task) {
            $taskName = $task->getName();
            $taskParamString = $paramStrings[$taskName];
            $taskSignature = trim(sprintf('%s %s', $taskName, $taskParamString));
            $padding = max(0, $maxTaskSignatureLength - strlen($taskSignature));
            $output .= sprintf(
                "%s%s # %s\n",
                $this->colorizer->bold($taskSignature),
                str_repeat(' ', $padding),
                $task->getDescription());
        }
        $this->process->output($output);
    }

    private function getParamString($params) {
        return trim(array_reduce($params, function($result, $param) {
            return $result .= sprintf("%s=? ", $param->getName());
        }, ''));
    }

}

