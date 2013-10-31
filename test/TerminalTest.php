<?php

require_once __DIR__ . '/util/BaseTestCase.php';

class TerminalTest extends BaseTestCase {

    public function setUp() {
        $container = new Mike\DependencyContainer;
        $this->deps = $container->getDependencies();
    }

    public function testListining1() {
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

    public function testListining2() {
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

        $this->deps->terminal->showTasks();
        return $result;
    }

}
