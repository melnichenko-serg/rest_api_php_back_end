<?php

namespace api\app\validators;


class Arr
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
		return is_array($data) ? true : false;
	}
}