<?php
require_once 'controllers/HomeController.php';
require_once 'controllers/AuthController.php';
require_once 'controllers/PostController.php';

$routes = [
    '/' => [
        'controller' => 'HomeController',
        'method' => 'index',
    ],
    '/register' => [
        'controller' => 'AuthController',
        'method' => 'register'
    ],
    '/login' => [
        'controller' => 'AuthController',
        'method' => 'login'
    ],
    '/logout' => [
        'controller' => 'AuthController',
        'method' => 'logout'
    ],
    '/handleRegister' => [
        'controller' => 'AuthController',
        'method' => 'handleRegister'
    ],
    '/handleLogin' => [
        'controller' => 'AuthController',
        'method' => 'handleLogin'
    ],
    //---
// Add the Post routes
'/posts' => [
    'controller' => 'PostController',
    'method' => 'index' // List all posts
],
'/posts/create' => [
    'controller' => 'PostController',
    'method' => 'create' // Show form to create a post
],
'/posts/store' => [
    'controller' => 'PostController',
    'method' => 'store' // Handle post creation
],
'/posts/show' => [
    'controller' => 'PostController',
    'method' => 'show' // View a post by ID
],
'/posts/edit' => [
    'controller' => 'PostController',
    'method' => 'edit' // Show form to edit a post
],
'/posts/update' => [
    'controller' => 'PostController',
    'method' => 'update' // Handle post update
],
'/posts/delete' => [
    'controller' => 'PostController',
    'method' => 'delete' // Handle post deletion
],


];
// Get the incoming url e.g www.example.com/user [/user]
$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); // /users
$route = $routes[$url];
if ($route) {
    $controller = new $route['controller'](); // new UserController()
    $method = $route['method']; // index
    $controller->$method(); // $controller->index()
} else {
    header("HTTP/1.0 404 Not Found");
    require 'views/utilities/404.php';
} 
?>
