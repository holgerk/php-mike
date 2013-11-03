<?php

namespace Mike;

class Task {

    public function __construct($args) {
        $this->name         = $args['name'];
        $this->description  = $args['description'];
        $this->dependencies = $args['dependencies'];
        $this->function     = $args['function'];
    }

    public function run($params) {
        if (!$this->function) {
            return null;
        }
        return call_user_func_array($this->function, $params);
    }

    public function getName() {
        return $this->name;
    }

    public function getDescription() {
        if (!$this->description) {
            $this->fetchDescriptionFromDocComment();
        }
        return $this->description;
    }

    public function getParams() {
        if (!$this->function) {
            return array();
        }
        $reflection = new \ReflectionFunction($this->function);
        return $reflection->getParameters();
    }

    public function getRequiredParams() {
        return array_filter($this->getParams(), function($param) {
            return !$param->isOptional();
        });
    }

    public function getDependencies() {
        return $this->dependencies;
    }

    private function fetchDescriptionFromDocComment() {
        if (!$this->function) {
            return '';
        }
        $reflection = new \ReflectionFunction($this->function);
        $comment = $reflection->getDocComment();
        $comment = trim(trim(trim($comment, '/'), '*'));
        $comment = implode("\n", array_map(function($line) {
            return trim(ltrim($line, '*'));
        }, explode("\n", $comment)));
        $this->description = $comment;
    }
}
