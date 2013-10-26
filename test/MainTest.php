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
                ->returns(null)
            ->create()
        );
        $this->deps->replace('process', $this->simpleMock('Mike\Process')
            ->expects('quit')
            ->create()
        );
        $this->deps->main->run();
    }

    public function testTaskIsExecuted() {
        task('test', function() use(&$wasRun) { $wasRun = true; });
        $this->setEnv(array('shellArgs' => array('test')));
        $this->deps->main->run();
        $this->assertTrue($wasRun);
    }


    // ===============
    // helper function
    // ---------------

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
