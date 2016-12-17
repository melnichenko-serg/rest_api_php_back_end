<?php

namespace api\app;

class Error
{
	private static $instance;
	private static $message = [];

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
		if (!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public static function setMessage($message)
	{
		self::$message[] = $message;
	}

	public static function getMessage()
	{
		return self::$message;
	}
}
