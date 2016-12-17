<?php

namespace api\app;


class Encryption
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

    public static function hash($data)
    {
        return password_hash($data, PASSWORD_DEFAULT, ['cost' => 13]);
    }

    public static function verify($password, $hash)
    {
        return password_verify($password, $hash);
    }
}
