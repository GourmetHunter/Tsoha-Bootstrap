<?php

$routes->get('/', function() {
    HelloWorldController::index();
});

$routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
});

$routes->get('/games', function() {
    HelloWorldController::gamelist();
});

$routes->get('/login', function() {
   HelloWorldController::login(); 
});