<?php

namespace Mike;

class TaskFileFinder {

    public function __construct($process, $throwUsageError) {
        $this->process = $process;
        $this->throwUsageError = $throwUsageError;

        $this->fileName = 'Mikefile';
    }

    /**
     * @todo make find file windows compatible
     */
    public function find() {
        $dir = $this->process->workingDirectory();
        do {
            $path = $dir . '/' . $this->fileName;
            if (file_exists($path)) {
                return $path;
            }
            $dir = dirname($dir);
        } while ($path != '//' . $this->fileName);
        call_user_func_array($this->throwUsageError, array("No {$this->fileName} found!"));
    }

}
