<?php

require_once __DIR__ . '/../vendor/autoload.php';

class ArgumentReaderTest extends SimpleMock_TestCase {

    public function setUp() {
        $container = new Mike\DependencyContainer;
        $this->deps = $container->getDependencies();
    }

    public function testReadArguments() {
        $this->deps->replace('process', $this->simpleMock('Mike\Process')
            ->expects('argv')
            ->returns(array('script.php', 'test', 'filter=db', 'clean'))
            ->create()
        );
        $this->assertEquals(
            array('test', array('filter' => 'db')),
            $this->deps->argumentReader->nextTaskData());
        $this->assertEquals(
            array('clean', array()),
            $this->deps->argumentReader->nextTaskData());
        $this->assertNull(
            $this->deps->argumentReader->nextTaskData());
    }

}
