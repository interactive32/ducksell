<?php

/**
 * View Helper
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */
function getControllerName() {

    $controller = '';

    if (app('request')->route()) {

        $action = app('request')->route()->getAction();
        $controller = str_replace("Controller", "", class_basename($action['controller']));

        list($controller, $action) = explode('@', $controller);

        $controller = strtolower($controller);
    }

    return $controller;
}
