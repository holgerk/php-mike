<?php

namespace Mike;

class Application {

    public function __construct(
            $taskFileFinder,
            $taskLoader,
            $process,
            $argumentReader,
            $taskRunner,
            $output,
            $throwUsageError) {
        $this->taskFileFinder  = $taskFileFinder;
        $this->taskLoader      = $taskLoader;
        $this->process         = $process;
        $this->argumentReader  = $argumentReader;
        $this->taskRunner      = $taskRunner;
        $this->output          = $output;
        $this->throwUsageError = $throwUsageError;
    }

    public function run() {
        if ($this->argumentReader->isFlagSet('help')) {
            $this->output->helpMessage();
            $this->process->quit(0);
            return;
        }

        $taskFile = null;
        if ($this->argumentReader->isFlagSet('file')) {
            $taskFile = $this->argumentReader->getFlagArgument('file');
        } else {
            $taskFile = $this->taskFileFinder->find();
        }

        $taskFile = $this->process->realpath($taskFile);
        $this->process->chdir(dirname($taskFile));
        $this->taskLoader->loadFile($taskFile);

        if ($this->argumentReader->isFlagSet('tasks')) {
            $this->output->showTasks();
            $this->process->quit(0);
            return;
        }

        $taskNames = $this->argumentReader->getTasks();
        if (count($taskNames) == 0 && $this->taskLoader->taskExist('default')) {
            $taskNames[] = 'default';
        }
        if (count($taskNames) == 0) {
            call_user_func($this->throwUsageError, 'No task given!');
        }
        foreach ($taskNames as $taskName) {
            $taskParams = $this->argumentReader->getTaskArgs($taskName);
            $this->taskRunner->run($taskName, $taskParams);
        }
    }

}
