<?php

require_once __DIR__ . '/util/BaseTestCase.php';

class TaskLoaderTest extends BaseTestCase {

    public function setUp() {
        $container = new Mike\DependencyContainer;
        $this->loader = $container->getDependencies()->taskLoader;
        $this->file = __DIR__ . '/fixtures/dir1/Mikefile';
    }

    public function testThatDescriptionIsFetchedFromDocComment() {
        $this->loader->loadFile($this->file);
        $task = $this->loader->getTask('task1');
        $this->assertEquals('desc1', $task->getDescription());
    }

    public function testThatDescriptionIsFilledByDescCall() {
        $this->loader->loadFile($this->file);
        $task = $this->loader->getTask('task2');
        $this->assertEquals('desc2', $task->getDescription());
    }

    public function testThatDescriptionIsEmpty() {
        $this->loader->loadFile($this->file);
        $task = $this->loader->getTask('task3');
        $this->assertEquals('', $task->getDescription());
    }

    /**
     * @expectedException Mike\UsageError
     * @expectedExceptionMessage Missing task: task42
     */
    public function testMissingTaskException() {
        $this->loader->loadFile($this->file);
        $task = $this->loader->getTask('task42');
    }

    public function testGetTasks() {
        task('t1');
        task('t2');
        $tasks = $this->loader->getTasks();
        $this->assertEquals(2, count($tasks));
    }

}
