<?php

require_once __DIR__ . '/../vendor/autoload.php';

class TaskLoaderTest extends SimpleMock_TestCase {

    public function setUp() {
        $container = new Mike\DependencyContainer;
        $this->loader = $container->getDependencies()->taskLoader;
        $this->file = __DIR__ . '/fixtures/dir1/Mikefile';
    }

    public function testThatTasksAreLoaded() {
        $this->loader->loadFile($this->file);
        $tasks = $this->loader->getTasks();
        $this->assertEquals(3, count($tasks));
    }

    public function testThatDescriptionIsFetchedFromDocComment() {
        $this->loader->loadFile($this->file);
        $tasks = $this->loader->getTasks();
        $this->assertEquals('desc1', $tasks['task1']->getDescription());
    }

    public function testThatDescriptionIsFilledByDescCall() {
        $this->loader->loadFile($this->file);
        $tasks = $this->loader->getTasks();
        $this->assertEquals('desc2', $tasks['task2']->getDescription());
    }

    public function testThatDescriptionIsEmpty() {
        $this->loader->loadFile($this->file);
        $tasks = $this->loader->getTasks();
        $this->assertEquals('', $tasks['task3']->getDescription());
    }

}
