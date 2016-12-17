<?php

namespace api\app\validators;


use api\app\Error;

class File
{
    private static $instance;

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
        !self::$instance && self::$instance = new self();
        return self::$instance;
    }

    public static function run($data, $param)
    {
        $arrParam = explode(".", $param);
        if (in_array("noRequired", $arrParam) && $data[key($data)]['error'] === 4) return true;
        if (!is_array($data) || empty($data) || empty(trim(key($data))) || $data[key($data)]['error'] === 4) return false;
        $file = $data[key($data)];
        $size = !empty($arrParam[2]) ? ($arrParam[2] * 1000) * 1024 : 1000000;
        if ($file['size'] > $size) {
            return false;
        }

        $fileType = $arrParam[0];

        $rulesMime = "api/app/params/files_type.php";
        if (!file_exists($rulesMime)) return false;
        /** @noinspection PhpIncludeInspection */
        $rulesMime = include_once $rulesMime;
        $ext = explode(".", $file['name']);
        $ext = array_pop($ext);
        if ((!in_array(mime_content_type($file['tmp_name']), $rulesMime[$fileType]['mime'])) || (!in_array($ext, $rulesMime[$fileType]['ext']))) return false;
        return true;
    }
}
