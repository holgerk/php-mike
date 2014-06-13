<?php

require_once __DIR__ . '/util/BaseTestCase.php';

class ColorizerTest extends BaseTestCase {

    public function setUp() {
        $container = new Mike\DependencyContainer;
        $this->deps = $container->getDependencies();
    }

    public static function colorizerDataProvider() {
        return array(
            array("\033[0;31mTEXT\033[0m", 'red', 'TEXT'),
            array("\033[0;31;1mTEXT\033[0m", 'redBold', 'TEXT'),
            array("\033[0;31;1;40mTEXT\033[0m", 'redBoldOnBlack', 'TEXT'),
        );
    }

    /**
     * @dataProvider colorizerDataProvider
     */
    public function testColorizer($expectedOutput, $method, $input) {
        $this->assertEquals($expectedOutput, $this->deps->colorizer->$method($input));
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Unsupported color: OnPink, in call: blueOnPink!
     */
    public function testColorizerException() {
        $this->deps->colorizer->blueOnPink('42');
    }

}
