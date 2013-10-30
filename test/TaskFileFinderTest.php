<?php

require_once __DIR__ . '/util/BaseTestCase.php';

class TaskFileFinderTest extends BaseTestCase {

    public function setUp() {
        $container = new Mike\DependencyContainer;
        $this->deps = $container->getDependencies();
        $this->fixtureDir = __DIR__ . '/fixtures';
        $this->expectedFilePath = $this->fixtureDir . '/dir1/Mikefile';
    }

    public function testShouldFindTaskFileInWorkingDirectory() {
        $this->deps->replace('process', $this->mock('Mike\Process')
            ->expects('workingDirectory')
            ->returns($this->fixtureDir . '/dir1')
            ->create()
        );
        $this->assertEquals($this->expectedFilePath, $this->deps->taskFileFinder->find());
    }

    public function testShouldFindTaskFileAboveWorkingDirectory() {
        $this->deps->replace('process', $this->mock('Mike\Process')
            ->expects('workingDirectory')
            ->returns($this->fixtureDir . '/dir1/dir2/dir3')
            ->create()
        );
        $this->assertEquals($this->expectedFilePath, $this->deps->taskFileFinder->find());
    }

    /**
     * @expectedException Mike\UsageError
     * @expectedExceptionMessage No Mikefile found!
     */
    public function testShouldNotFindTaskFile() {
        $this->deps->replace('process', $this->mock('Mike\Process')
            ->expects('workingDirectory')
            ->returns($this->fixtureDir)
            ->create()
        );
        $this->assertNull($this->deps->taskFileFinder->find());
    }

}
