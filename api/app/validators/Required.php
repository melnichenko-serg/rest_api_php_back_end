<?php

namespace api\app\validators;

use api\app\Error;
use api\app\filters\Clear;

class Required
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

	public static function run($data)
	{
		return (trim($data) !== "") ? true : false;
	}

	private function __wakeup()
	{
	}

	private function __clone()
	{
	}
}
