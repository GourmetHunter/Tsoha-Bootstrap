<?php

$routes->get('/', function() {
    HelloWorldController::index();
});

$routes->get('/account', function() {
    HelloWorldController::account();
});

$routes->get('/games', function() {
    HelloWorldController::gamelist();
});

$routes->get('/login', function() {
   HelloWorldController::login(); 
});

$routes->get('/showgame', function() {
    HelloWorldController::showgame();
});

$routes->get('/editgame', function() {
    HelloWorldController::editgame();
});

$routes->get('/addgame', function() {
    HelloWorldController::addgame();
});

$routes->get('/suggest', function() {
    HelloWorldController::suggestgame();
});

$routes->get('/suggestions', function() {
    HelloWorldController::suggestions();
});