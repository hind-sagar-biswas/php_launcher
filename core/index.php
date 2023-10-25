<?php
// Including the initializer
require_once './init.php';

// Enabling the router
ROUTER->route(REQUEST);

session_write_close();