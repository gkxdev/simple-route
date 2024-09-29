<?php
require_once __DIR__ . '/Route.php';

$route = new Route;

$route->get('/', function() {
    return 'Welcome!';
});

$route->get('/name/(\w+)', function($name) {
    return 'Name: ' . strip_tags($name);
});

$route->post('/id/(\d+)', function(int $id) {
    return 'ID: ' . $id;
});

$route->notFound(function() {
    // 404 - Not Found!
});

$route->run();
