# Cat Framework
A light weight micro framework for php
Made for APIs

![alt text](https://github.com/andregans/code_logotype/blob/main/PHP%20Logotype.png?raw=true)

Why yet another php framework? All I want in this world is a simple api php framework, I do not need 10,000 lines of php for some bloated html templating api that slows the framework to a crawl (those who shall remain unnamed)
A person only needs two things in this world, Routing and a database.

# Example

```php
<?php

// Require
require_once __DIR__ . '/../framework/Include.php';
require_once __DIR__ . '/../framework/database/Database.php';

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
    return ['message' => 'Hello, from Cat Framework!'];
});

$router->post('/api/echo', function ($data) {
    return ['you_sent' => $data];
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
$db->close();
$router->run();
```
