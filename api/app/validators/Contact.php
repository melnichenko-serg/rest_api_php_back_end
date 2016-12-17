<?php

namespace api\app\validators;


class Contact
{
    private static $instance;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        !self::$instance && self::$instance = new self();
        return self::$instance;
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    public static function run($data)
    {
        $data = strtolower(trim($data));
        return ($data === "phone" || $data === "email" || $data === "post") ? true : false;
    }
}