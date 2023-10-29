<?php

use Core\Security\Random;
use Hindbiswas\Phpdotenv\StdIO;

$command_count = 1;

$commands = ['composer dump-autoload'];
$cmd = implode(' && ', $commands);
shell_exec($cmd);

if (file_exists(ROOTPATH . 'package.json')) {
    shell_exec('npm install');
}

$vars = [
    'APP_NAME' => 'My App | PHP Launcher',
    'APP_ROOT' => normalizePath('/' . basename(ROOTPATH)),
    'APP_URL' => 'http://localhost',
    'APP_DEBUG' => 'true',
    'APP_ROUTE_SYSTEM' => 'raw',
    'APP_KEY' => Random::hex(),
    'DATABASE_HOST' => 'localhost',
    'DATABASE_DATABASE' => 'launcher_db',
    'DATABASE_USERNAME' => 'root',
    'DATABASE_PASSWORD' => '',
];
$vars['APP_URL'] = (str_ends_with($vars['APP_URL'], '/')) ? $vars['APP_URL'] : $vars['APP_URL'] . '/';

// Get template from .env.example
$file = ROOTPATH . "shell/.env.example";
$fp = fopen($file, "r");
$template = fread($fp, filesize($file));
fclose($fp);

foreach ($vars as $key => $value) {
    $template = str_replace("{{{$key}}}", $value, $template);
}

// Set to .env
$file = ROOTPATH . "shell/.env";
$fp = fopen($file, "w");
fwrite($fp, $template);
fclose($fp);

// Generate .htaccess
$file = ROOTPATH . ".htaccess";
$fp = fopen($file, "w");
$base_for_htaccess = (str_starts_with($vars['APP_ROOT'], '/')) ? $vars['APP_ROOT'] : '/' . $vars['APP_ROOT'];
fwrite($fp, "RewriteEngine On
RewriteBase " . $base_for_htaccess . "

RewriteCond %{DOCUMENT_ROOT}/assets/$1 -f
RewriteRule ^(.+) assets/$1 [L]

RewriteCond %{DOCUMENT_ROOT}/node_modules/$1 -f
RewriteRule ^(.+) node_modules/$1 [L]

RewriteCond %{THE_REQUEST} \s/assets/ [NC,OR]
RewriteCond %{THE_REQUEST} \s/node_modules/ [NC,OR]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^ core/index.php [L,QSA]");
fclose($fp);

StdIO::put(StdIO::green('✅ Setup completed'));
StdIO::put('');
StdIO::put('=', 100);
StdIO::put(StdIO::blue('INFO: ') . 'To initalize database, run `' . StdIO::yellow('php launch migrate flush') . '`');
StdIO::put(StdIO::blue('INFO: ') . 'To seed database,      run `' . StdIO::yellow('php launch migrate seed') . '`');
StdIO::put('=', 100);
