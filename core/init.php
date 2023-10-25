<?php

declare(strict_types=1);

session_start();
// session_regenerate_id(true);

// Paths
define('ROOTPATH', __DIR__ . '/../');
define('ERR_PAGES', ROOTPATH . 'shell/errors/');

// Pre requires
require_once ROOTPATH . 'vendor/autoload.php';

// Class Uses
use Core\Base\Request;
use Core\Base\RequestType;
use Core\Router\Router;
use Core\Security\Csrf;
use Hindbiswas\Phpdotenv\DotEnv;

// Load Env Variables
$dotEnv = new DotEnv(ROOTPATH . 'shell'); // Location where .env file exists
$dotEnv->load();
// Handle CSRF tokens
$_csrf_token_key = (isset($_SESSION['key'])) ? $_SESSION['key'] : null;
$_csrf_token_hash = (isset($_SESSION['hash'])) ? $_SESSION['hash'] : null;

//// CONSTANTS
// DEVELOPER
define('DEV_NAME', 'Hind Sagar Biswas');
define('DEV_URL', 'https://hind-sagar-biswas.github.io/portfolio/');
define('DEV_CONTACT', '+880-1956-899240');
// Tokens & Keys
define('CSRF_ENABLED', $_ENV['CSRF_ENABLED']);
define('CSRF_TOKEN', Csrf::generateToken());
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
define('REQUEST', (isset($_SERVER['REQUEST_URI'])) ? new Request() : null);
define('ALERT', (isset($_SESSION['message'])) ? $_SESSION['message'] : null);

if (REQUEST) $Router = new Router();

// Requires
require_once ROOTPATH . 'core/Func/functions.php';

// Shells
if (REQUEST) require_once ROOTPATH . 'shell/routes/' . ((REQUEST->type === RequestType::WEB) ? 'web.php' : 'api.php');
require_once ROOTPATH . 'shell/extend.php';

// Router
if (REQUEST) define('ROUTER', $Router);
unset($Router);
unset($_SESSION['message']);