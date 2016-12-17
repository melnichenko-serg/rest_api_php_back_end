<?php
/**
 * *ey | Date: 22.11.16 | Time: 11:58
 */

namespace api\app\validators;


use api\app\Error;

class Integer
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

	public static function run($data)
	{
		$data = trim($data);
        return filter_var($data, FILTER_VALIDATE_INT);
	}
}
