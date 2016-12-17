<?php

namespace api\app\validators;


class Date
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
		return true;
		// todo create validator
	}
}