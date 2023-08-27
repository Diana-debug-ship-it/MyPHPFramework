<?php

error_reporting(-1);

use vendor\core\Router;
use app\controllers\MainController;
use app\controllers\PostsController;
use app\controllers\PostsNewController;

$query = $_SERVER['QUERY_STRING'];

define("WWW", __DIR__);
define("CORE", dirname(__DIR__) . "/vendor/core");
define("ROOT", dirname(__DIR__));
define("APP", dirname(__DIR__) . "/app");
define("LAYOUT", 'default');

require '../vendor/core/Router.php';
require '../vendor/libs/functions.php';
//require '../app/controllers/Main.php';
//require '../app/controllers/Posts.php';
//require '../app/controllers/PostsNew.php';

spl_autoload_register(function ($class) {
    $file = ROOT . '/' . str_replace('\\', '/', $class) . '.php';
    //$file = APP . "/controllers/{$class}.php";
    if (file_exists($file)) {
        require_once $file;
    }
});

$router = new Router();

Router::add('^page/(?P<action>[a-z-]+)/(?P<alias>[a-z-]+)$', ['controller' => 'Page']);
Router::add('^page/(?P<alias>[a-z-]+)$', ['controller' => 'Page', 'action' => 'view']);


//default routs
Router::add('^$', ['controller' => 'Main', 'action' => 'Index']);
Router::add('^(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?$');
//Router::add('<controller:[a-z-]+>/<action:([a-z-]+)>');

Router::dispatch($query);