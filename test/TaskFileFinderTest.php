<?php

require_once __DIR__ . '/../vendor/autoload.php';

class TaskFileFinderTest extends SimpleMock_TestCase {

    public function setUp() {
        $this->deps = include __DIR__ . '/../src/deps.php';
        $this->fixtureDir = __DIR__ . '/fixtures';
        $this->expectedFilePath = $this->fixtureDir . '/dir1/Mikefile';
    }

    public function testShouldFindTaskFileInWorkingDirectory() {
        $this->deps->replace('process', $this->simpleMock('Jiggle\Process')
            ->expects('workingDirectory')
            ->returns($this->fixtureDir . '/dir1')
            ->create()
        );
        $this->assertEquals($this->expectedFilePath, $this->deps->taskFileFinder->find());
    }

    public function testShouldFindTaskFileAboveWorkingDirectory() {
        $this->deps->replace('process', $this->simpleMock('Jiggle\Process')
            ->expects('workingDirectory')
            ->returns($this->fixtureDir . '/dir1/dir2/dir3')
            ->create()
        );
        $this->assertEquals($this->expectedFilePath, $this->deps->taskFileFinder->find());
    }

    public function testShouldNotFindTaskFile() {
        $this->deps->replace('process', $this->simpleMock('Jiggle\Process')
            ->expects('workingDirectory')
            ->returns($this->fixtureDir)
            ->create()
        );
        $this->assertNull($this->deps->taskFileFinder->find());
    }

}
