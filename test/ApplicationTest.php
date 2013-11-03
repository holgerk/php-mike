<?php

require_once __DIR__ . '/util/BaseTestCase.php';

class ApplicationTest extends BaseTestCase {

    public function setUp() {
        $container = new Mike\DependencyContainer;
        $this->deps = $container->getDependencies();
        $this->workingDirectory = __DIR__ . '/fixtures/dir1';
    }

    public function testWhenNoTaskFileIsFoundProcessIsTerminated() {
        $this->deps->replace('taskFileFinder',
            $this->mock('Mike\TaskFileFinder')
                ->expects('find')->raises(new Mike\UsageError('No Mikefile found!'))
                ->create()
        );
        $this->deps->replace('output', $this->mock('Mike\Output')
            ->expects('errorMessage')->with($this->stringContains('No Mikefile found!'))
            ->create()
        );
        $this->deps->replace('process', $this->simpleMock('Mike\Process')
            ->expects('argv')->returns(array('script.php'))
            ->expects('quit')
            ->create()
        );
        call_user_func($this->deps->runApplication);
    }

    public function testWhenNoTaskIsGivenErrorIsShown() {
        $this->deps->replace('output', $this->mock('Mike\Output')
            ->expects('errorMessage')->with($this->stringContains('No task given!'))
            ->create()
        );
        $this->deps->replace('process', $this->mock('Mike\Process')
            ->expects('argv')->returns(array('script.php'))
            ->expects('workingDirectory')->returns($this->workingDirectory)
            ->expects('quit')
            ->create()
        );
        call_user_func($this->deps->runApplication);
    }

    public function testWhenHelpFlagIsSetHelpIsShown() {
        $this->deps->replace('output', $this->mock('Mike\Output')
            ->expects('helpMessage')
            ->create()
        );
        $this->deps->replace('process', $this->mock('Mike\Process')
            ->expects('argv')->returns(array('script.php', '-h'))
            ->expects('quit')
            ->create()
        );
        call_user_func($this->deps->runApplication);
    }

    public function testWhenTaskFlagIsSetTaskAreShown() {
        $this->deps->replace('output', $this->mock('Mike\Output')
            ->expects('showTasks')
            ->create()
        );
        $this->deps->replace('process', $this->mock('Mike\Process')
            ->expects('argv')->returns(array('script.php', '-T'))
            ->expects('workingDirectory')->returns($this->workingDirectory)
            ->expects('quit')
            ->create()
        );
        call_user_func($this->deps->runApplication);
    }

    public function testTaskIsExecutedWithParams() {
        $result = null;
        task('test', function($p1, $p2) use(&$result) { $result = $p1 + $p2; });
        $this->setEnv(array('shellArgs' => array('test', 'p1=40', 'p2=2')));
        call_user_func($this->deps->runApplication);
        $this->assertEquals(42, $result);
    }

    public function testDefaultTaskIsExecutedWhenNoTaskIsGiven() {
        $wasRun = false;
        task('default', function() use(&$wasRun) { $wasRun = true; });
        $this->setEnv(array('shellArgs' => array()));
        call_user_func($this->deps->runApplication);
        $this->assertTrue($wasRun);
    }

    public function testTaskFileCouldBeSetViaFileFlag() {
        $this->deps->replace('process', $this->mock('Mike\Process')
            ->expects('argv')->returns(array('script.php', '-f', 'OtherMikefile', 'noop'))
            ->create()
        );
        $this->deps->replace('taskLoader', $this->mock('Mike\TaskLoader')
            ->expects('loadFile')->with('OtherMikefile')
            ->create()
        );
        $this->deps->replace('taskRunner', $this->mock('Mike\TaskRunner')
            ->expects('run')->with('noop')
            ->create()
        );
        call_user_func($this->deps->runApplication);
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

        $this->deps->replace('process', $this->mock('Mike\Process')
            ->expects('argv')->returns($shellArgs)
            ->expects('workingDirectory')->returns($workingDirectory)
            ->create()
        );
    }

}
