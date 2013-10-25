<?php

require_once __DIR__ . '/../vendor/autoload.php';

class TaskLoaderTest extends SimpleMock_TestCase {

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

}
