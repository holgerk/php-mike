<?php

namespace Mike;

class Task {

    public function __construct($args) {
        $this->name         = $args['name'];
        $this->description  = $args['description'];
        $this->dependencies = $args['dependencies'];
        $this->function     = $args['function'];
    }

    public function getDescription() {
        if (!$this->description) {
            $this->fetchDescriptionFromDocComment();
        }
        return $this->description;
    }

    public function getDependencies() {
        return $this->dependencies;
    }

    public function run($params) {
        $callParams = array();
        foreach ($this->getParams() as $param) {
            $paramName = $param->getName();
            if (isset($params[$paramName])) {
                $callParams[] = $params[$paramName];
            } else if ($param->isOptional()) {
                $callParams[] = $param->getDefaultValue();
            } else {
                throw new Exception("Missing param: $paramName for task: {$this->name}!");
            }
        }
        return call_user_func_array($this->function, $callParams);
    }

    public function getParams() {
        $reflection = new \ReflectionFunction($this->function);
        return $reflection->getParameters();
    }

    private function fetchDescriptionFromDocComment() {
        $reflection = new \ReflectionFunction($this->function);
        $comment = $reflection->getDocComment();
        $comment = trim(trim(trim($comment, '/'), '*'));
        $comment = implode("\n", array_map(function($line) {
            return trim(ltrim($line, '*'));
        }, explode("\n", $comment)));
        $this->description = $comment;
    }
}
