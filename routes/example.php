<?php

/**
 * Example of a get route - access http://localhost:8080/hello-world
 * Assuming you have run php -S localhost:8080 index.php
 * @description Testing swagger annotations -> see example.php under routes
 */
\Tina4\Get::add("/hello-world", function(\Tina4\Response $response){
    return $response ("Hello World!");
});


/**
 * Example of a get route with inline params - access http://localhost:8080/hello-world/test
 * Assuming you have run php -S localhost:8080 index.php
 */
\Tina4\Get::add("/hello-world/{name}", function($name, \Tina4\Response $response){
    return $response ("Hello World {$name}!");
});

/**
 * Example of route calling class , method
 * Note the swagger annotations will go in the class
 */
\Tina4\Get::add("/test/class", ["Example", "route"]);

/**
 * Example of route calling class , static method
 * Note the swagger annotations will go in the class
 */
\Tina4\Get::add("/test/class/static", ["Example", "routeStatic"]);