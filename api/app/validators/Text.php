<?php

namespace api\app\validators;


class Text
{
    private static $instance;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    public static function getInstance()
    {
        !self::$instance && self::$instance = new self();
        return self::$instance;
    }

    /* doing nothing */
    public static function run($data)
    {
        return true;
    }
}