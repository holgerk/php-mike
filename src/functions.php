<?php


// ====================================================
// proxy/convinience functions for TaskLoader class
// ----------------------------------------------------

function task() {
    return call_user_func_array(
        array(Mike\DependencyContainer::getTaskLoader(), __FUNCTION__), func_get_args());
}

function desc() {
    return call_user_func_array(
        array(Mike\DependencyContainer::getTaskLoader(), __FUNCTION__), func_get_args());
}
