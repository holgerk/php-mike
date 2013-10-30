<?php

require_once __DIR__ . '/../../vendor/autoload.php';

class BaseTestCase extends SimpleMock_TestCase {

    protected function mock($class) {
        return $this->simpleMock($class)->strict()->complete();
    }

}
