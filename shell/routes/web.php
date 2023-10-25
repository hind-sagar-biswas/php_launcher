<?php

use Core\Router\Router;

$Router->add_routes(
    Router::get('/')->name('home')->call('index'),
);
