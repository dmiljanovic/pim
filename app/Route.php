<?php

namespace App;

/**
 * Class Route
 * @package App
 */
class Route
{
    /**
     * Array of valid routes.
     *
     * @var array
     */
    public static $validRoutes = array();

    /**
     * Static method for setting route and invoke function for creating view.
     *
     * @param string $route
     * @param $function
     */
    public static function set($route, $function)
    {
        self::$validRoutes[] = $route;
        if ($_GET['url'] == $route) {
            $function->__invoke();
        }
    }
}
