<?php

require_once __DIR__ . '/../vendor/autoload.php';

class InteractiveParamReaderTest extends SimpleMock_TestCase {

    public function setUp() {
        $container = new Mike\DependencyContainer;
        $this->deps = $container->getDependencies();
    }

    public function testRead() {
        $this->deps->replace('process',
            $this->simpleMock('Mike\Process')->strict()->complete()
                ->expects('readline')->with($this->stringContains('p2'))->returns('42')
                ->create()
        );
        $this->assertEquals(42, $this->deps->interactiveParamReader->read('p2'));
    }

}
