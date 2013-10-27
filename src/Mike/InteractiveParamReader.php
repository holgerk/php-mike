<?php

namespace Mike;

class InteractiveParamReader {

    public function __construct($process) {
        $this->process = $process;
    }

    public function read($paramName) {
        $prompt = "Please provide a value for $paramName:";
        return $this->process->readline($prompt);
    }

}
