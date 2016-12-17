<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 06.12.16
 * Time: 15:15
 */

namespace api\app;


trait Range
{
    public function getRage(string $httpRange)
    {
        $httpRange = explode("-", $httpRange);
        return [
            "start" => array_shift($httpRange),
            "finish" => array_shift($httpRange),
        ];
    }
}