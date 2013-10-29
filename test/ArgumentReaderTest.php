<?php

require_once __DIR__ . '/../vendor/autoload.php';

class ArgumentReaderTest extends SimpleMock_TestCase {

    public function setUp() {
        $container = new Mike\DependencyContainer;
        $this->deps = $container->getDependencies();
    }

    public function testReadArguments() {
        $this->deps->replace('process', $this->simpleMock('Mike\Process')->strict()->complete()
            ->expects('argv')->returns(array('script.php', 'test', 'filter=db', 'clean'))
            ->create()
        );
        $this->assertEquals(
            array('test', 'clean'),
            $this->deps->argumentReader->getTasks());
        $this->assertEquals(
            array('filter' => 'db'),
            $this->deps->argumentReader->getTaskArgs('test'));
        $this->assertEquals(
            array(),
            $this->deps->argumentReader->getTaskArgs('clean'));
    }

}
