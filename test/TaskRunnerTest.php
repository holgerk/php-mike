<?php

require_once __DIR__ . '/util/BaseTestCase.php';

class TaskRunnerTest extends BaseTestCase {

    public function setUp() {
        $container = new Mike\DependencyContainer;
        $this->deps = $container->getDependencies();
        $this->deps->replace('process', $this->mock('Mike\Process')
            ->expects('argv')->returns(array('script.php'))
            ->create()
        );
    }

    public function testThatTaskIsRun() {
        $wasRun = false;
        task('test', function() use(&$wasRun) { $wasRun = true; });
        $this->deps->taskRunner->run('test');
        $this->assertTrue($wasRun);
    }

    public function testTaskWithoutFunctionRunItsDeps() {
        $wasRun = false;
        task('test', function() use(&$wasRun) { $wasRun = true; });
        task('default', 'test');
        $this->deps->taskRunner->run('default');
        $this->assertTrue($wasRun);
    }

    public function testThatDependentTaskIsRun() {
        $taskChain = array();
        task('build', function() use(&$taskChain) { $taskChain[] = 'build'; });
        task('test', 'build', function() use(&$taskChain) { $taskChain[] = 'test'; });
        $this->deps->taskRunner->run('test');
        $this->assertEquals(array('build', 'test'), $taskChain);
    }

    public function testThatTaskIsRunWithParams() {
        $result = null;
        task('test', function($p1, $p2) use(&$result) { $result = $p1 + $p2; });
        $this->deps->taskRunner->run('test', array('p1' => 40, 'p2' => 2));
        $this->assertEquals(42, $result);
    }

    public function testWhenParamIsMissingItIsFetchedFromInteractiveParamReader() {
        $result = null;
        task('test', function($p1, $p2) use(&$result) { $result = $p1 + $p2; });
        $this->deps->replace('interactiveParamReader',
            $this->mock('Mike\InteractiveParamReader')
                ->expects('read')
                    ->with('p2')
                    ->returns('2')
                ->create()
        );
        $this->deps->taskRunner->run('test', array('p1' => 40));
        $this->assertEquals(42, $result);
    }

    /**
     * @expectedException Mike\UsageError
     * @expectedExceptionMessage Circular dependencies: a > b > c > b!
     */
    public function testThatRunnerThrowsOnCircularDependencies() {
        task('a', 'b', function() {});
        task('b', 'c', function() {});
        task('c', 'b', function() {});
        $this->deps->taskRunner->run('a');
    }

    public function testThatDependenciesAreRunOnce() {
        $runCount = 0;
        task('build', function() use(&$runCount) { $runCount++; });
        task('test', 'build');
        $runner = $this->deps->taskRunner;
        $runner->run('build');
        $this->assertEquals(1, $runCount);
        $runner->run('test');
        $this->assertEquals(1, $runCount);
    }

    public function testThatDirectlyInvokedTasksCanRunMultipleTimes() {
        $runCount = 0;
        task('build', function() use(&$runCount) { $runCount++; });
        $runner = $this->deps->taskRunner;
        $runner->run('build');
        $runner->run('build');
        $this->assertEquals(2, $runCount);
    }

    public function testThatTaskCanRunOtherTaskAndFetchReturnValue() {
        $result = 0;
        task('build', function()  { return 42; });
        task('test', function() use(&$result) {
            $result = run('build');
        });
        $this->deps->taskRunner->run('test');
        $this->assertEquals(42, $result);
    }

    public function testThatTaskCanRunOtherTaskWithParams() {
        $result = 0;
        task('build', function($p1)  { return 40 + $p1; });
        task('test', function() use(&$result) {
            $result = run('build', array('p1' => 2));
        });
        $this->deps->taskRunner->run('test');
        $this->assertEquals(42, $result);
    }

    public function testThatTaskParamsCouldBeProvidedByParamDeclaration() {
        $this->deps->replace('interactiveParamReader', $this->mock('Mike\InterActiveParamReader')
            ->create()
        );

        param('p1', 2);

        $result = 0;
        task('build', function($p1)  { return 40 + $p1; });
        task('test', function() use(&$result) {
            $result = run('build');
        });
        $this->deps->taskRunner->run('test');
        $this->assertEquals(42, $result);
    }

    public function testThatParamDeclarationCouldBeLazyEvaluatedWithClosures() {
        $this->deps->replace('interactiveParamReader', $this->mock('Mike\InterActiveParamReader')
            ->create()
        );

        $evaluated = false;
        param('p1', function() use(&$evaluated) {
            $evaluated = true;
            return 2;
        });

        $this->assertFalse($evaluated);

        task('test', function($p1)  { return 40 + $p1; });
        $result = $this->deps->taskRunner->run('test');
        $this->assertEquals(42, $result);
        $this->assertTrue($evaluated);
    }

    public function testThatParamClosuresCanDependOnEachOther() {
        $this->deps->replace('interactiveParamReader', $this->mock('Mike\InterActiveParamReader')
            ->create()
        );

        param('p1', function($p2) { return $p2 + 2; });
        param('p2', 40);

        task('test', function($p1)  { return $p1; });
        $result = $this->deps->taskRunner->run('test');
        $this->assertEquals(42, $result);
    }

    public function testThatUnresolvableParamDepenciesAreResolvedViaInteractiveParamReader() {
        $this->deps->replace('interactiveParamReader', $this->mock('Mike\InterActiveParamReader')
            ->expects('read')->with('p2')->returns(40)
            ->create()
        );

        param('p1', function($p2) { return $p2 + 2; });

        task('test', function($p1)  { return $p1; });
        $result = $this->deps->taskRunner->run('test');
        $this->assertEquals(42, $result);
    }

}
