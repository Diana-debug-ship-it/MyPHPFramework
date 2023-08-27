<?php

namespace vendor\core;

class Router
{
    /**массив со всеми маршрутами, таблица маршрутов*/
    protected static $routes = [];

    /**тут текущий маршрут*/
    protected static $route = [];


    /**
     * добавляет маршрут в таблицу маршрутов
     *
     * @param string $regexp регулярное выражение маршрута
     * @param array $route маршрут ([controller, action, params])
     */
    public static function add(string $regexp, array $route = [])
    {
        self::$routes[$regexp] = $route;
    }

    /** возвращает маршруты из таблицы маршрутов */
    public static function getRoutes()
    {
        return self::$routes;
    }

    /** возвращает текущий маршрут */
    public static function getRoute()
    {
        return self::$route;
    }

    /** ищет URL в таблице маршрутов
     * @param string $url входящий URl
     * @return boolean
     */
    public static function matchRoute($url)
    {
        foreach (self::$routes as $pattern => $route) {
            if (preg_match("#$pattern#i", $url, $matches)) {
                foreach ($matches as $key => $value) {
                    if (is_string($key)) {
                        $route[$key] = $value;
                    }
                }
                if (!isset($route['action'])) {
                    $route['action'] = 'index';
                }
                $route['controller'] = self::upperCamelCase($route['controller']);
                self::$route = $route;
                return true;
            }
        }
        return false;
    }


    /**
     *перенаправляет URL по корректному маршруту
     * @param string $url входящиц URL
     * @return void
     */
    public static function dispatch($url)
    {
        $url = self::removeQueryString($url);
        if (self::matchRoute($url)) {
            $controller = 'app\controllers\\' . self::$route['controller'] . 'Controller';
            if (class_exists($controller)) {
                $cObj = new $controller(self::$route);
                $action = self::lowerCamelCase(self::$route['action']) . 'Action';
                if (method_exists($cObj, $action)) {
                    $cObj->$action();
                    $cObj->getView();
                } else {
                    echo "<p>Метод <b>$action</b> не найден</p>";
                }
            } else {
                echo "<p>Контроллер <b>$controller</b> не найден</p>";
            }
        } else {
            http_response_code(404);
            include '404.html';
        }
    }

    /**
     * преобразует неявный параметр из адресной строки
     * в CamelCase
     * @param $name
     * @return string
     */
    protected static function upperCamelCase($name): string
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $name)));
    }

    /**
     * преобразует неявный параметр из адресной строки
     * в lowerCamelCase
     * @param $name
     * @return string
     */
    protected static function lowerCamelCase($name): string
    {
        return lcfirst(self::upperCamelCase($name));
    }

    /**
     * берет URL и возвращает только неявные GET параметры
     * @param $url
     * @return string
     */
    protected static function removeQueryString($url)
    {
        if ($url) {
            $params = explode('&', $url, 2);
            if (false === strpos($params[0], '=')) {
                return rtrim($params[0], '/');
            } else {
                return '';
            }
        }
    }
}