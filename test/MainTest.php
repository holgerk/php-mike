<?php

require_once __DIR__ . '/../vendor/autoload.php';

class MainTest extends SimpleMock_TestCase {

    public function setUp() {
        $this->deps = include __DIR__ . '/../src/deps.php';
        $this->deps->replace('taskLoader', 'dummy null');
    }

    public function testWhenNoTaskFileIsFoundProcessIsTerminated() {
        $this->deps->replace('taskFileFinder', $this->simpleMock('Jiggle\TaskFileFinder')
            ->expects('find')
            ->returns(null)
            ->create()
        );
        $this->deps->replace('process', $this->simpleMock('Jiggle\Process')
            ->expects('quit')
            ->create()
        );
        $this->deps->main->run();
    }

    public function testWhenTaskFileIsFoundItIsLoaded() {
        $this->deps->replace('taskFileFinder', $this->simpleMock('Jiggle\TaskFileFinder')
            ->expects('find')
            ->returns('/path/task-file')
            ->create()
        );
        $this->deps->replace('process', $this->simpleMock('Jiggle\Process')
            ->expects('quit')
            ->never()
            ->create()
        );
        $this->deps->replace('taskLoader', $this->simpleMock('Jiggle\TaskLoader')
            ->expects('loadFile')
            ->with('/path/task-file')
            ->create()
        );
        $this->deps->main->run();
    }
}
