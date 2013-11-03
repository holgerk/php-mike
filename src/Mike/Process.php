<?php

namespace Mike;

class Process {

    public function quit($exitCode = 0) {
        if (strpos($_SERVER['SCRIPT_FILENAME'], 'phpunit') !== false) {
            return;
        }
        exit($exitCode);
    }

    public function workingDirectory() {
        return getcwd();
    }

    public function chdir($directory) {
        chdir($directory);
    }

    public function realpath($path) {
        return realpath($path);
    }

    public function argv() {
        global $argv;
        return $argv;
    }

    public function readline($message) {
        return readline($message);
    }

    public function output($message) {
        echo $message;
    }

}
