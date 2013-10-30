<?php

require_once __DIR__ . '/util/BaseTestCase.php';

class InteractiveParamReaderTest extends BaseTestCase {

    public function setUp() {
        $container = new Mike\DependencyContainer;
        $this->deps = $container->getDependencies();
    }

    public function testRead() {
        $this->deps->replace('process',
            $this->mock('Mike\Process')
                ->expects('readline')->with($this->stringContains('p2'))->returns('42')
                ->create()
        );
        $this->assertEquals(42, $this->deps->interactiveParamReader->read('p2'));
    }

}
