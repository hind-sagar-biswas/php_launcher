<?php

declare(strict_types=1);

// Start a PHP session
session_start();
// Regenerate session ID (uncomment to enable)
session_regenerate_id(true);

// Define paths
define('ROOTPATH', __DIR__ . '/../');
define('ERR_PAGES', ROOTPATH . 'shell/errors/');

// Require the Composer autoload file
require_once ROOTPATH . 'vendor/autoload.php';

// Use statements
use Core\Base\Request;
use Core\Router\Router;
use Core\Security\Csrf;
use Core\Base\RequestType;
use Core\Router\RouteSystem;
use Hindbiswas\Phpdotenv\DotEnv;

// Load environment variables from .env file
$dotEnv = new DotEnv(ROOTPATH . 'shell'); // Location where .env file exists
$dotEnv->load();

// Handle CSRF tokens
$_csrf_token_key = (isset($_SESSION['key'])) ? $_SESSION['key'] : null;
$_csrf_token_hash = (isset($_SESSION['hash'])) ? $_SESSION['hash'] : null;

// Constants
// Developer information
define('DEV_NAME', 'Hind Sagar Biswas');
define('DEV_URL', 'https://hind-sagar-biswas.github.io/portfolio/');
define('DEV_CONTACT', '+880-1956-899240');
// Security
define('CSRF_ENABLED', ($_ENV['CSRF_ENABLED'] === 'true') ? true : false);
// App Info
define('APP_KEY', $_ENV['APP_KEY']);
define('APP_NAME', $_ENV['APP_NAME']);
define('APP_DEBUG', $_ENV['APP_DEBUG']);
define('APP_ROOT', $_ENV['APP_ROUTE_ROOT']);
define('APP_URL', $_ENV['APP_URL'] . APP_ROOT);
define('APP_API_ROOT', $_ENV['API_ROUTE_ROOT']);
define('APP_API', APP_URL . APP_API_ROOT);
// App folders/Paths
define('TEMPLATES', ROOTPATH . 'shell/templates/');
// Database configuration
define('DB_HOST', $_ENV['DB_HOST']);
define('DB_PORT', $_ENV['DB_PORT']);
define('DB_NAME', $_ENV['DB_DATABASE']);
define('DB_USER', $_ENV['DB_USERNAME']);
define('DB_PASS', $_ENV['DB_PASSWORD']);
// Request
define('APP_ROUTE_SYS', RouteSystem::tryFrom($_ENV['APP_ROUTE_SYSTEM']) ?? RouteSystem::RAW);
define('REQUEST', (isset($_SERVER['REQUEST_URI'])) ? new Request() : null);
define('ALERT', (isset($_SESSION['message'])) ? $_SESSION['message'] : null);

// Create a Router object if REQUEST is available
if (REQUEST) {
    // Header declarations
    header('X-Content-Type-Options: nosniff');
    header('X-XSS-Protection: 1; mode=block');
    if ($_ENV['X_FRAME_ENABLED'] !== 'true') header('X-Frame-Options: SAMEORIGIN');

    // Router Obj
    $Router = new Router(APP_ROUTE_SYS);
}

// Require utility functions
require_once ROOTPATH . 'core/Func/functions.php';

// Include route files if using a non-RAW route system
if (REQUEST && APP_ROUTE_SYS !== RouteSystem::RAW) {
    require_once ROOTPATH . 'shell/routes/' . ((REQUEST->type === RequestType::WEB) ? 'web.php' : 'api.php');
}

// Include extension file
require_once ROOTPATH . 'shell/extend.php';

// Set ROUTER constant if REQUEST is available, and clean up variables
if (REQUEST) {
    define('ROUTER', $Router);
    unset($Router);
    unset($_SESSION['message']);
}
