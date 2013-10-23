<?php

namespace Mike;

class Process {

    public function quit() {
        exit;
    }

    public function workingDirectory() {
        return getcwd();
    }

}
