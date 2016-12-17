<?php

namespace api\app;


class Bootstrap
{
    private $namespace = "api\\modules\\";
    private $controller = null;
    private $action = null;
    private $param = null;
    private $module;
    use Auth;

    /**
     * Bootstrap constructor.
     * @param $routes
     */
    public function __construct($routes)
    {
        $uriRequest = $this->parserUri();
        $this->app($routes, $uriRequest);
    }

    public function parserUri()
    {
        return trim(filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL), "/");
    }

    public function app($routes, $uriRequest)
    {
        $arrUriInRequest = explode("/", $uriRequest);

        if ($arrUriInRequest[0] === '') {
            $this->controller = "api\\modules\\home\\Home";
            $controller = new $this->controller();
            /** @noinspection PhpUndefinedMethodInspection */
            return $controller->indexAction();
        }

        foreach ($routes as $routeItem => $routeValues) {
            $arrUriInRoute = explode("/", $routeItem);
            if (count($arrUriInRequest) !== count($arrUriInRoute)) continue;
            foreach ($arrUriInRoute as $number => $part) {
                if ($part{0} === ':') {
                    $paramName = substr($part, 1);
                    if (isset($routeValues['params'][$paramName])) {
                        if ($routeValues['params'][$paramName] === 'int') {
                            if (!filter_var($arrUriInRequest[$number], FILTER_VALIDATE_INT)) {
                                continue 2;
                            }
                            $this->param = $arrUriInRequest[$number];
                        }
                        $this->param = filter_var(trim($arrUriInRequest[$number]), FILTER_SANITIZE_STRING);
                    }
                } else if ($part !== $arrUriInRequest[$number]) {
                    if (!strpos($arrUriInRequest[$number], "?")) continue 2;
                    list($urlRequest, $getRequestParams) = explode("?", $arrUriInRequest[$number]);
                    if ($part !== $urlRequest) continue 2;
                    if (!isset($getRequestParams)) continue 2;
                }
            }
            if (!isset($routeValues[$_SERVER['REQUEST_METHOD']])) {
                http_response_code(405);
                Error::setMessage('Method das not exist');
                return false;
            }
            if (isset($routeValues[$_SERVER['REQUEST_METHOD']]['auth'])) {
                if (!$this::auth()) {
                    http_response_code(401);
                    Error::setMessage("Please Log-in or Sign-in");
                    return false;
                }
            }
//            if (!empty($_REQUEST)) $this->param = $_REQUEST;
//            $this->module = isset($routeValues[$_SERVER['REQUEST_METHOD']]['module']) ? "modules\\" . strtolower($routeValues[$_SERVER['REQUEST_METHOD']]['module']) . '\\' : 'app\\';
            $this->module = strtolower($routeValues[$_SERVER['REQUEST_METHOD']]['module']) . "\\";
            $this->controller = ucfirst($routeValues[$_SERVER['REQUEST_METHOD']]['controller']);
            $this->action = $routeValues[$_SERVER['REQUEST_METHOD']]['action'];
            $this->controller = $this->namespace . $this->module . $this->controller;
            $this->controller = new $this->controller();
            if (method_exists($this->controller, $this->action)) {
                return $this->controller->{$this->action}($this->param);
            } else {
                http_response_code(404);
                Error::setMessage('Action das not exist');
                return false;
            }
        }
        http_response_code(404);
        return false;
    }
}
