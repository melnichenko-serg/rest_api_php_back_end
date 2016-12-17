<?php

namespace api\app\validators;


use api\app\Error;
use api\app\filters\Clear;

class MinLength
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

	public static function run($data, $param)
	{
		return (mb_strlen(trim($data)) < $param) ? false : true;
	}
}
