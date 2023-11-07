<?php

use Hindbiswas\Phpdotenv\StdIO;

$VERSION = 'v0.3.2-beta ';
$AUTHOR = 'Hind Sagar Biswas ';
$REPO = 'https://github.com/hind-sagar-biswas/php_launcher ';


define('SP', "     ");
define('OFFSET', SP . SP);

StdIO::put(OFFSET . "                                                                                               ");
StdIO::put(OFFSET . "██████╗ ██╗  ██╗██████╗     ██╗      █████╗ ██╗   ██╗███╗   ██╗ ██████╗██╗  ██╗███████╗██████╗ ");
StdIO::put(OFFSET . "██╔══██╗██║  ██║██╔══██╗    ██║     ██╔══██╗██║   ██║████╗  ██║██╔════╝██║  ██║██╔════╝██╔══██╗");
StdIO::put(OFFSET . "██████╔╝███████║██████╔╝    ██║     ███████║██║   ██║██╔██╗ ██║██║     ███████║█████╗  ██████╔╝");
StdIO::put(OFFSET . "██╔═══╝ ██╔══██║██╔═══╝     ██║     ██╔══██║██║   ██║██║╚██╗██║██║     ██╔══██║██╔══╝  ██╔══██╗");
StdIO::put(OFFSET . "██║     ██║  ██║██║         ███████╗██║  ██║╚██████╔╝██║ ╚████║╚██████╗██║  ██║███████╗██║  ██║");
StdIO::put(OFFSET . "╚═╝     ╚═╝  ╚═╝╚═╝         ╚══════╝╚═╝  ╚═╝ ╚═════╝ ╚═╝  ╚═══╝ ╚═════╝╚═╝  ╚═╝╚══════╝╚═╝  ╚═╝");
StdIO::put(OFFSET . "                                                                                               ");


$length = 95;

StdIO::put(OFFSET . put('=', $length, true));
StdIO::put(OFFSET . str_pad('|-> Version:', 15) . str_pad($VERSION, $length - 16, ' ', STR_PAD_LEFT) . '|');
StdIO::put(OFFSET . str_pad('|-> Author :', 15) . str_pad($AUTHOR, $length - 16, ' ', STR_PAD_LEFT) . '|');
StdIO::put(OFFSET . str_pad('|-> Github :', 15) . str_pad($REPO, $length - 16, ' ', STR_PAD_LEFT) . '|');
StdIO::put(OFFSET . put('=', $length, true));
StdIO::put('');