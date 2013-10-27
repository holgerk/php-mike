<?php

namespace Mike;

class Process {

    public function quit($exitCode = 0) {
        exit($exitCode);
    }

    public function workingDirectory() {
        return getcwd();
    }

    public function argv() {
        global $argv;
        return $argv;
    }

    public function readline($message) {
        return readline($message);
    }

}
