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

    public function run() {
        return call_user_func_array($this->function, array());
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
