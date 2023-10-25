<?php

use Core\Router\Router;

$Router->add_routes(
    Router::get('/')->name('main')->call('index'),
);