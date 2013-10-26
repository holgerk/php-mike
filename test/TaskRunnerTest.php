<?php

require_once __DIR__ . '/../vendor/autoload.php';

class TaskRunnerTest extends SimpleMock_TestCase {

    public function setUp() {
        $container = new Mike\DependencyContainer;
        $this->deps = $container->getDependencies();
    }

    public function testThatTaskIsRun() {
        $wasRun = false;
        task('test', function() use(&$wasRun) { $wasRun = true; });
        $this->deps->taskRunner->run('test');
        $this->assertTrue($wasRun);
    }

    public function testThatDependentTaskIsRun() {
        $taskChain = array();
        task('build', function() use(&$taskChain) { $taskChain[] = 'build'; });
        task('test', 'build', function() use(&$taskChain) { $taskChain[] = 'test'; });
        $this->deps->taskRunner->run('test');
        $this->assertEquals(array('build', 'test'), $taskChain);
    }

    public function testThatTaskIsRunWithParams() {
        $result = null;
        task('test', function($p1, $p2) use(&$result) { $result = $p1 + $p2; });
        $this->deps->taskRunner->run('test', array('p1' => 40, 'p2' => 2));
        $this->assertEquals(42, $result);
    }

}
