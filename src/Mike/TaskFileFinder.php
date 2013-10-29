<?php

namespace Mike;

class TaskFileFinder {

    public function __construct($process, $throwUsageError) {
        $this->process = $process;
        $this->throwUsageError = $throwUsageError;

        $this->fileName = 'Mikefile';
    }

    public function find() {
        $dir = $this->process->workingDirectory();
        do {
            $currentDir = $dir;
            $path = $dir . '/' . $this->fileName;
            if (file_exists($path)) {
                return $path;
            }
            $dir = dirname($dir);
        } while ($currentDir != $dir);
        call_user_func($this->throwUsageError, "No {$this->fileName} found!");
    }

}
