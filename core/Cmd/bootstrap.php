<?php

use Core\Security\Random;
use Hindbiswas\Phpdotenv\StdIO;

require_once __DIR__ . '/info.php';

$command_count = 1;

$commands = ['composer dump-autoload'];
$cmd = implode(' && ', $commands);
shell_exec($cmd);

if (file_exists(ROOTPATH . 'package.json')) {
    StdIO::put(StdIO::yellow($command_count++ . "| ") . 'Installing node modules...');
    shell_exec('npm install');
    StdIO::put(StdIO::green('✅ Node modules installed'));
}

$consent = strtolower(StdIO::get('Do you want to run setup?', 'Y', ['Y', 'n']));
if ($consent == 'no' || $consent == 'n') {
    StdIO::put(StdIO::red('Cancelling the setup!'));
    exit();
}
StdIO::put(StdIO::green('Running the setup...') . PHP_EOL);

// Take inputs
StdIO::put(StdIO::yellow($command_count++ . "| ") . 'Please input correct details...');
$vars = [
    'APP_NAME' => StdIO::get('App Name', 'My App | PHP Launcher'),
    'APP_ROOT' => normalizePath(StdIO::get('App root', '/' . basename(ROOTPATH))),
    'APP_URL' => StdIO::get('App URL', 'http://localhost'),
    'APP_DEBUG' => StdIO::get('Debug mode', 'true', ['true', 'false']),
    'APP_ROUTE_SYSTEM' => (StdIO::get('Router system', 'raw', ['raw', 'ctr']) === 'ctr') ? 'controlled' : 'raw',
    'APP_KEY' => Random::hex(),
    'DATABASE_HOST' => StdIO::get('Database host', 'localhost'),
    'DATABASE_DATABASE' => StdIO::get('Database database', 'launcher_db'),
    'DATABASE_USERNAME' => StdIO::get('Database username', 'root'),
    'DATABASE_PASSWORD' => StdIO::get('Database password', ''),
];
$vars['APP_URL'] = (str_ends_with($vars['APP_URL'], '/')) ? $vars['APP_URL'] : $vars['APP_URL'] . '/';

// Get template from .env.example
StdIO::put(StdIO::yellow($command_count++ . "| ") . 'Reading ENV variables tempplate...');
$file = ROOTPATH . "shell/.env.example";
$fp = fopen($file, "r");
$template = fread($fp, filesize($file));
fclose($fp);

foreach ($vars as $key => $value) {
    $template = str_replace("{{{$key}}}", $value, $template);
}

// Set to .env
StdIO::put(StdIO::yellow($command_count++ . "| ") . 'Generating .env file...');
$file = ROOTPATH . "shell/.env";
$fp = fopen($file, "w");
fwrite($fp, $template);
fclose($fp);

// Generate .htaccess
StdIO::put(StdIO::yellow($command_count++ . "| ") . 'Generating .htaccess file...');
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

StdIO::put(StdIO::green('✅ Required files generated'));
StdIO::put(StdIO::green('✅ Setup completed'));
StdIO::put('');
StdIO::put('=', 100);
StdIO::put(StdIO::blue('INFO: ') . 'To initalize database, run `' . StdIO::yellow('php launch migrate flush') . '`');
StdIO::put(StdIO::blue('INFO: ') . 'To seed database,      run `' . StdIO::yellow('php launch migrate seed') . '`');
StdIO::put('=', 100);
StdIO::put('');
StdIO::put('😘 Thanks for using PHP launcher, we wish you the best of luck - ' . StdIO::blue($AUTHOR));
