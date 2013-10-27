<?php

require_once __DIR__ . '/../vendor/autoload.php';

class MainTest extends SimpleMock_TestCase {

    public function setUp() {
        $container = new Mike\DependencyContainer;
        $this->deps = $container->getDependencies();
        $this->workingDirectory = __DIR__ . '/fixtures/dir1';
    }

    public function testWhenNoTaskFileIsFoundProcessIsTerminated() {
        $this->deps->replace('taskFileFinder', $this->simpleMock('Mike\TaskFileFinder')
            ->expects('find')
                ->raises(new Mike\UsageError('No Mikefile found!'))
            ->create()
        );
        $this->deps->replace('terminal', $this->simpleMock('Mike\Terminal')
            ->expects('errorMessage')
                ->with($this->stringContains('No Mikefile found!'))
            ->create()
        );
        $this->deps->replace('process', $this->simpleMock('Mike\Process')
            ->expects('quit')
            ->create()
        );
        $this->deps->main->run();
    }

    public function testWhenNoTaskIsGivenErrorIsShown() {
        $this->deps->replace('terminal', $this->simpleMock('Mike\Terminal')
            ->expects('errorMessage')
                ->with($this->stringContains('No task given!'))
            ->create()
        );
        $this->deps->replace('process', $this->simpleMock('Mike\Process')
            ->expects('argv')
                ->returns(array('script.php'))
            ->expects('workingDirectory')
                ->returns($this->workingDirectory)
            ->expects('quit')
            ->create()
        );
        $this->deps->main->run();
    }

    public function testTaskIsExecutedWithParams() {
        $result = null;
        task('test', function($p1, $p2) use(&$result) { $result = $p1 + $p2; });
        $this->setEnv(array('shellArgs' => array('test', 'p1=40', 'p2=2')));
        $this->deps->main->run();
        $this->assertEquals(42, $result);
    }

    public function testDefaultTaskIsExecutedWhenNoTaskIsGiven() {
        $wasRun = false;
        task('default', function() use(&$wasRun) { $wasRun = true; });
        $this->setEnv(array('shellArgs' => array()));
        $this->deps->main->run();
        $this->assertTrue($wasRun);
    }


    // ================
    // helper functions
    // ----------------

    private function setEnv($options) {
        $shellArgs = array('script.php');
        if (isset($options['shellArgs'])) {
            $shellArgs = array_merge($shellArgs, $options['shellArgs']);
        }
        $workingDirectory = $this->workingDirectory;
        if (isset($options['workingDirectory'])) {
            $workingDirectory = $options['workingDirectory'];
        }

        $this->deps->replace('process', $this->simpleMock('Mike\Process')
            ->expects('argv')
                ->returns($shellArgs)
            ->expects('workingDirectory')
                ->returns($workingDirectory)
            ->create()
        );
    }

}
