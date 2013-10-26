<?php

require_once __DIR__ . '/../vendor/autoload.php';

class TaskRunnerTest extends SimpleMock_TestCase {

    public function setUp() {
        $container = new Mike\DependencyContainer;
        $this->deps = $container->getDependencies();
    }

    public function testTaskRunner() {
        $wasRun = false;
        $task = new Mike\Task(array(
            'name'         => '',
            'description'  => '',
            'dependencies' => array(),
            'function'     => function() use(&$wasRun) { $wasRun = true; }
        ));
        $this->deps->taskRunner->run($task);
        $this->assertTrue($wasRun);
    }

}
