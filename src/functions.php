<?php


// =========================================================
// proxy/convinience functions for TaskLoader and TaskRunner
// ---------------------------------------------------------

function desc() {
    return call_user_func_array(
        array(Mike\DependencyContainer::getTaskLoader(), __FUNCTION__), func_get_args());
}

function task() {
    return call_user_func_array(
        array(Mike\DependencyContainer::getTaskLoader(), __FUNCTION__), func_get_args());
}

function run() {
    return call_user_func_array(
        array(Mike\DependencyContainer::getTaskRunner(), __FUNCTION__), func_get_args());
}

function param() {
    return call_user_func_array(
        array(Mike\DependencyContainer::getTaskRunner(), __FUNCTION__), func_get_args());
}
