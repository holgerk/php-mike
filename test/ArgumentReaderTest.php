<?php

require_once __DIR__ . '/util/BaseTestCase.php';

class ArgumentReaderTest extends BaseTestCase {

    public function setUp() {
        $container = new Mike\DependencyContainer;
        $this->deps = $container->getDependencies();
    }

    public function testGetTasks() {
        $this->setArgs('test', 'filter=db', 'clean');
        $this->assertEquals(array('test', 'clean'), $this->deps->argumentReader->getTasks());
    }

    public function testGetTaskArgs() {
        $this->setArgs('test', 'filter=db', 'clean');
        $this->assertEquals(
            array('filter' => 'db'),
            $this->deps->argumentReader->getTaskArgs('test'));
        $this->assertEquals(
            array(),
            $this->deps->argumentReader->getTaskArgs('clean'));
    }

    public function testIsFlagSetShortName() {
        $this->setArgs('-T');
        $this->assertTrue($this->deps->argumentReader->isFlagSet('tasks'));
    }

    public function testIsFlagSetLongName() {
        $this->setArgs('--tasks');
        $this->assertTrue($this->deps->argumentReader->isFlagSet('tasks'));
    }

    public function testGetFlagArgument() {
        $this->setArgs('-f', 'Taskfile');
        $this->assertEquals('Taskfile', $this->deps->argumentReader->getFlagArgument('file'));
    }

    private function setArgs() {
        $args = array_merge(array('script'), func_get_args());
        $this->deps->replace('process', $this->mock('Mike\Process')
            ->expects('argv')->returns($args)
            ->create()
        );
    }

}
