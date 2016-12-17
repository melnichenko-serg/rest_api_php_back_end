<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 06.12.16
 * Time: 15:22
 */

namespace api\app;


trait QueryString
{
    public function getQueryString(string $queryString)
    {
        parse_str($queryString, $arrayQueryParams);
        return $arrayQueryParams;
    }
}