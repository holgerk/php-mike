<?php

require_once __DIR__ . '/util/BaseTestCase.php';
require_once __DIR__ . '/util/NullColorizer.php';

class OutputTest extends BaseTestCase {

    public function setUp() {
        $container = new Mike\DependencyContainer;
        $this->deps = $container->getDependencies();
        $this->deps->replace('colorizer', new NullColorizer());
    }

    public function testListining() {
        desc('desc1');
        task('task1');
        desc('desc2');
        task('task2');
        $this->assertEquals(''
            . "task1 # desc1\n"
            . "task2 # desc2\n"
            , $this->getTaskListing()
        );
    }

    public function testListiningWithLongTaskName() {
        desc('desc1');
        task('longtask1');
        desc('desc2');
        task('task2');
        $this->assertEquals(''
            . "longtask1 # desc1\n"
            . "task2     # desc2\n"
            , $this->getTaskListing()
        );
    }

    public function testListiningWithTaskParams() {
        desc('desc1');
        task('task1');
        desc('desc2');
        task('task2', function($param1) {});
        $this->assertEquals(''
            . "task1          # desc1\n"
            . "task2 param1=? # desc2\n"
            , $this->getTaskListing()
        );
    }

    private function getTaskListing() {
        $result = '';
        $this->deps->replace('process', $this->mock('Mike\Process')
            ->expects('output')->with(
                $this->callback(function ($message) use(&$result) {
                    $result = $message;
                    return true;
                })
            )
            ->create()
        );

        $this->deps->output->showTasks();
        return $result;
    }

}
