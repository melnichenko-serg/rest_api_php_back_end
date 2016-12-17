<?php

namespace api\app;

use PDO;
use PDOException;

class DataBase
{
	private static $instance = null;
	private $connect;
	private $error;

	/**
	 * DataBase constructor.
	 */
	private function __construct()
	{
		$this->error = Error::getInstance();
		try {
			if (!file_exists("api/app/params/db.php")) {
				http_response_code(500);
				die();
			}
            /** @noinspection PhpIncludeInspection */
            $params = require_once "api/app/params/db.php";
			$dsn = "mysql:host={$params['DBHost']};dbname={$params['DBName']};charset={$params['DBCharset']}";
			$options = [
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			];
			$this->connect = new PDO($dsn, $params['DBUser'], $params['DBPass'], $options);
		} catch (PDOException $exception) {
			Error::setMessage($exception->getMessage());
			return http_response_code(500);
		}
	}

	public static function getInstance()
	{
		!self::$instance && self::$instance = new self();
		return self::$instance;
	}

	/**
	 * @return PDO get connection to the DB
	 */
	public function getConnect(): PDO
	{
		return $this->connect;
	}

	private function __clone()
	{
	}

	private function __wakeup()
	{
	}
}
