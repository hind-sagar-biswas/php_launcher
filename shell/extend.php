<?php


use Core\Auth\Logger;
use Core\Base\Export;
use Shell\Database\Table\UsersTable;

$_auth = new Logger(
    auth_table: new UsersTable(),
    hash_pass: true,
    identifier_regex: null,
    passkey_regex: null,
);

$logged_in = $_auth->is_logged_in();

Export::vars(['_auth', 'logged_in']);