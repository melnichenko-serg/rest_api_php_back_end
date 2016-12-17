<?php

namespace api\app;


trait Auth
{
    public static function auth()
    {
        return (isset($_COOKIE['user_id'])) ? true : false;
    }
}