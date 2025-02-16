<?php

// Require
require_once __DIR__ . '/framework/Include.php';
require_once __DIR__ . '/framework/database/Database.php';

// Require controllers if wanted

$router = new Router();

$db = new Database(
    $host = "127.0.0.1",
    $port = "3306",
    $user = "example_user",
    $pass = "example_password",
    $dbname = "example_name",
    $driver = "mysql" // Not needed here since default is mysql
);


// Example routes
$router->get('/api/hi', function () {
    echo json_encode(['message' => 'Hello, from Cat Framework!']);

    return;
});

$router->get('/api/echo/@message', function ($message) {
    echo json_encode(['you_sent' => $message]);
    return;
});

$router->get('/api/user', function ($data) use ($db) { // Dependency Injection for database
    
    $results = $db->prepare("SELECT * FROM users WHERE id = :id", 
    [
        ":id" => 1
    ])->run()->fetch();

    return ['message' => $results];
});

$router->get('/api/user/@uid', function ($data) use ($db) { // Dependency Injection for database
    $results = $db->prepare("SELECT * FROM users WHERE id = :id", [":id" => 1])->run()->fetch();

    return ['message' => $results];
});

// Custom 404 error (not needed)
$router->setNotFound(function () {
    http_response_code(404);
    echo json_encode(['error' => "No Cat Found :c"]);
});

// Done
// $db->close();
$router->run();