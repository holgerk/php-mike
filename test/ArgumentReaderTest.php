<?php

require_once __DIR__ . '/util/BaseTestCase.php';

class ArgumentReaderTest extends BaseTestCase {

    public function setUp() {
        $container = new Mike\DependencyContainer;
        $this->deps = $container->getDependencies();
    }

    public function testReadArguments() {
        $this->deps->replace('process', $this->mock('Mike\Process')
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
