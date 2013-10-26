<?php

require_once __DIR__ . '/../vendor/autoload.php';

class MainTest extends SimpleMock_TestCase {

    public function setUp() {
        $container = new Mike\DependencyContainer;
        $this->deps = $container->getDependencies();
        $this->deps->replace('taskLoader', 'dummy null');
    }

    public function testWhenNoTaskFileIsFoundProcessIsTerminated() {
        $this->deps->replace('taskFileFinder', $this->simpleMock('Mike\TaskFileFinder')
            ->expects('find')
                ->returns(null)
            ->create()
        );
        $this->deps->replace('process', $this->simpleMock('Mike\Process')
            ->expects('quit')
            ->create()
        );
        $this->deps->main->run();
    }

    public function SKIPPtestWhenTaskFileIsFoundItIsLoaded() {
        $this->deps->replace('taskFileFinder', $this->simpleMock('Mike\TaskFileFinder')
            ->expects('find')
                ->returns('/path/task-file')
            ->create()
        );
        $this->deps->replace('taskLoader', $this->simpleMock('Mike\TaskLoader')
            ->expects('loadFile')
                ->with('/path/task-file')
            ->create()
        );
        $this->deps->main->run();
    }

    public function SKIPPtestThatAllArgumentsAreRead() {
        $this->findTaskFile();
        $this->loadTaskFile();
        $this->deps->replace('argumentReader', $this->simpleMock('Mike\ArgumentReader')
            ->expects('getTasks')
                ->returns(array('task1', 'task2'))
            ->expects('getTaskArgs')
                ->with('task1')
                ->with('task2')
            ->create()
        );
        $this->deps->main->run();
    }

    public function SKIPPtestThatTasksFetchedFromLoader() {
        $this->findTaskFile();
        $this->deps->replace('taskLoader', $this->simpleMock('Mike\TaskLoader')
            ->expects('loadFile')
            ->expects('getTask')
                ->with('task1')
                ->with('task2')
            ->create()
        );
        $this->readArguments();
        $this->deps->main->run();
    }

    public function SKIPPtestThatFetchedTasksAreExecuted() {
        $this->findTaskFile();
        $this->loadAndFetchTasks();
        $this->readArguments();
        $this->deps->replace('taskRunner', $this->simpleMock('Mike\TaskRunner')
            ->expects('run')
                ->with('task-obj-1')
                ->with('task-obj-2')
            ->create()
        );
        $this->deps->main->run();
    }


    // ===============
    // helper function
    // ---------------

    private function findTaskFile() {
        $this->deps->replace('taskFileFinder', $this->simpleMock('Mike\TaskFileFinder')
            ->expects('find')
                ->returns('/path/task-file')
            ->create()
        );
    }

    private function loadTaskFile() {
        $this->deps->replace('taskLoader', $this->simpleMock('Mike\TaskLoader')
            ->expects('loadFile')
                ->with('/path/task-file')
            ->create()
        );
    }

    private function readArguments() {
        $this->deps->replace('argumentReader', $this->simpleMock('Mike\ArgumentReader')
            ->expects('getTasks')
                ->returns(array('task1', 'task2'))
            ->expects('getTaskArgs')
                ->with('task1')
                ->with('task2')
            ->create()
        );
    }

    private function loadAndFetchTasks() {
        $this->deps->replace('taskLoader', $this->simpleMock('Mike\TaskLoader')
            ->expects('loadFile')
            ->expects('getTask')
                ->with('task1')
                ->returns('task-obj-1')
                ->with('task2')
                ->returns('task-obj-2')
            ->create()
        );
    }

}
