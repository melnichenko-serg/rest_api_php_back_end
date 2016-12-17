<?php

namespace api\app\validators;

use api\app\DataBase;
use api\app\Error;
use api\app\filters\Clear;
use Exception;
use PDOException;

class Unique
{
    private static $instance;
    private static $db;

    private function __construct()
    {
        self::$db = DataBase::getInstance()->getConnect();
    }

    private function __wakeup()
    {
    }

    private function __clone()
    {
    }

    public static function getInstance()
    {
        !self::$instance && self::$instance = new self();
        return self::$instance;
    }

    public static function run($data, $param)
    {
        $db = DataBase::getInstance()->getConnect();
        $data = strtolower(trim($data));
        $arrParam = explode(".", $param);

        $tableNameFromParam = Clear::run(array_shift($arrParam));
        $fieldNameFromParam = Clear::run(strtolower(array_shift($arrParam)));

        try {
            $query = "select {$fieldNameFromParam} from {$tableNameFromParam}";
            $stmt = $db->prepare($query);
            $stmt->execute();
            foreach ($stmt->fetchAll() as $item) {
                if ($item[$fieldNameFromParam] === $data) return false;
            }
            return true;
        } catch (PDOException $exception) {
	        return false;
        }
    }
}
