<?php

namespace api\app\validators;


use api\app\DataBase;
use api\app\filters\Clear;

class Confirm
{
    private static $instance;

    private function __construct()
    {
    }

    private function __wakeup()
    {
    }

    private function __clone()
    {
    }

    public static function getInstance()
    {
        !self::$instance && self::$instance = new self();
        return self::$instance;
    }

    public static function run($field, $confirm)
    {
        return ($field !== $confirm) ? false : true;
    }
}