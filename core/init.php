<?php
session_start();
session_regenerate_id(true);

// Paths
define('ROOTPATH', __DIR__ . '/../');
define('ERR_PAGES', ROOTPATH . 'shell/errors/');

// Pre requires
require_once ROOTPATH . 'vendor/autoload.php';

// Class Uses
use Dotenv\Dotenv;

// Load Env Variables
$dotenv = Dotenv::createImmutable(ROOTPATH . 'shell');
$dotenv->safeLoad();
//// CONSTANTS
// Tokens & Keys
define('CSRF_ENABLED', $_ENV['CSRF_ENABLED']);
// App Info
define('APP_NAME', $_ENV['APP_NAME']);
define('APP_DEBUG', $_ENV['APP_DEBUG']);
define('APP_ROOT', $_ENV['APP_ROUTE_ROOT']);
define('APP_URL', $_ENV['APP_URL'] . APP_ROOT);
define('APP_API_ROOT', $_ENV['API_ROUTE_ROOT']);
// Database configuration
define('DB_HOST', $_ENV['DB_HOST']);
define('DB_PORT', $_ENV['DB_PORT']);
define('DB_NAME', $_ENV['DB_DATABASE']);
define('DB_USER', $_ENV['DB_USERNAME']);
define('DB_PASS', $_ENV['DB_PASSWORD']);

// Requires
require_once ROOTPATH . 'core/Func/functions.php';
