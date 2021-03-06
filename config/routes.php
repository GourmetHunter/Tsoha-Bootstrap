<?php

$routes->get('/', function() {
    BasicController::index();
});

$routes->get('/games/:page/:rule', function($page, $rule) {
    GameController::gamelist($page, $rule);
});

$routes->post('/search', function() {
    GameController::search();
});

$routes->get('/login', function() {
   BasicController::login(); 
});

$routes->post('/profile', function() {
   BasicController::account(); 
});

$routes->post('/suggest', function() {
   SuggestionController::suggest(); 
});

$routes->post('/registeruser', function() {
    BasicController::register(); 
});

$routes->get('/register', function() {
    BasicController::getregister(); 
});

$routes->get('/profile', function() {
   BasicController::getAccount(); 
});

$routes->get('/logout', function() {
    BasicController::logout(); 
});

$routes->get('/removesuggestion/:id', function($id) {
    SuggestionController::adminRemoveSuggestion($id);
});

$routes->post('/removesuggestion', function() {
   SuggestionController::remove();
});

$routes->get('/game/:id', function($id) {
    GameController::showgame($id);
});

$routes->get('/addgame/:id', function($id) {
    SuggestionController::moveToGame($id); 
});

$routes->post('/listgame', function() {
   SuggestionController::listGame(); 
});

$routes->get('/addgame', function() {
    SuggestionController::addgame();
});

$routes->get('/suggest', function() {
    SuggestionController::suggestgame();
});

$routes->get('/editgame/:id', function($id) {
    GameController::editgame($id);
});

$routes->get('/removegame/:id', function($id) {
    GameController::deletegame($id);
});

$routes->post('/editgame/', function() {
    GameController::saveedit();
});

$routes->get('/suggestions', function() {
    SuggestionController::suggestions();
});

$routes->post('/review', function() {
    ReviewController::review(); 
});

$routes->get('/removereview/:id/:game', function($id, $game) {
    ReviewController::deleteReview($id, $game); 
});