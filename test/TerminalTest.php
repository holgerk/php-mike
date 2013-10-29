<?php

require_once __DIR__ . '/../vendor/autoload.php';

class TerminalTest extends SimpleMock_TestCase {

    public function setUp() {
        $container = new Mike\DependencyContainer;
        $this->deps = $container->getDependencies();
    }

    public function testTaskListing() {
        $this->deps->replace('process', $this->simpleMock('Mike\Process')->strict()->complete()
            ->expects('output')
                ->with(''
                    . "task1 desc1\n"
                    . "task2 desc2\n"
                )
            ->create()
        );
        desc('desc1');
        task('task1');
        desc('desc2');
        task('task2');
        $this->deps->terminal->showTasks();
    }

}
