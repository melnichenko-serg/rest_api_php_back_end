<?php

namespace api\app\validators;


use api\app\Encryption;

class Password
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
		$data = trim($data);
		return Encryption::hash($data);
	}
}