<?php

namespace api\app\filters;


class Clear
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
        $filteredData = null;
        if (!is_array($data)) {
            $data = trim($data);
            $filteredData = (strpos($data, "@")) ?
                filter_var($data, FILTER_SANITIZE_EMAIL) :
                filter_var($data, FILTER_SANITIZE_STRING);
        } else {
            foreach ($data as $item => $value) {
                if (!is_array($value)) {
                    $filteredData[$item] = (strpos($value, "@")) ?
                        filter_var($value, FILTER_SANITIZE_EMAIL) :
                        filter_var($value, FILTER_SANITIZE_STRING);
                }
            }
        }
        return $filteredData;
	}
}
