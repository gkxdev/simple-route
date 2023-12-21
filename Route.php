<?php
/**
 * Route
 * @author Gökhan Kaya <gkxdev@gmail.com>
 */

class Route {
    private $routes = [];
    private $notFound;

    public function __construct() {
        $this->notFound = fn() => null;
    }

    public function get($pattern, $callback) {
        $this->addRoute('GET', $pattern, $callback);
    }

    public function post($pattern, $callback) {
        $this->addRoute('POST', $pattern, $callback);
    }

    public function notFound($callback) {
        $this->notFound = $callback;
    }

    public function run() {
        $matched = false;

        foreach ($this->routes as $route) {
            list($method, $pattern, $callback) = $route;

            if ($method != $this->getRequestMethod())
                continue;

            if (preg_match('@^' . $pattern . '$@i', $this->getRequestPath(), $params)) {
                array_shift($params);

                $matched = true;
                break;
            }
        }

        if (!$matched) {
            http_response_code(404);
            $callback = $this->notFound;
        }

        echo $this->callback($callback, $params ?? []);
    }

    private function addRoute($method, $pattern, $callback) {
        $this->routes[] = array($method, $pattern, $callback);
    }

    private function getRequestMethod() {
        return $_SERVER['REQUEST_METHOD'];
    }

    private function getRequestPath() {
        $parts = array_diff_assoc(
            explode('/', trim($_SERVER['REQUEST_URI'], '/')),
            explode('/', trim($_SERVER['SCRIPT_NAME'], '/'))
        );

        if (empty($parts)) {
            return '/';
        }

        $path = implode('/', $parts);

        if (($position = strpos($path, '?')) !== false) {
            $path = substr($path, 0, $position);
        }

        return '/' . $path;
    }

    private function callback($callback, array $params = []) {
        if (is_array($callback)) {
            list($controller, $method) = $callback;
            $callback = [new $controller, $method];
        }

        return call_user_func_array($callback, $params);
    }
}