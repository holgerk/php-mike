<?php

class NullColorizer {
    function __call($method, $args) { return $args[0]; }
}