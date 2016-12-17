<?php
/**
 * Created by PhpStorm
 * User: Serg
 * Date: 11.12.16
 * Time: 15:12
 */

//print "I am here";

use api\app\Bootstrap;
use api\app\Error;

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$routes = 'api/app/params/routes.php';
$autoload = 'api/app/autoload.php';

define("HOME", "http://any_url/");

/** @noinspection PhpIncludeInspection */
require_once $autoload;

try {
	/** @noinspection PhpIncludeInspection */
	new Bootstrap(require_once $routes);
} catch (Exception $exception) {
	print json_encode($exception->getMessage());
}

if (count(Error::getMessage()) > 0) print json_encode(Error::getMessage());

