<?php

// Uncomment the codes below to enable Logger [Make sure to have valid DB Connection]

// use Core\Auth\Logger;
// use Core\Base\Export;
// use Shell\Database\Table\LoginTokensTable;
// use Shell\Database\Table\UsersTable;

// $_auth = new Logger(
//     auth_table: new UsersTable(),
//     token_table: new LoginTokensTable(),
//     hash_pass: true,
//     identifier_regex: null,
//     passkey_regex: null,
// );

// $logged_in = $_auth->is_logged_in();

// Export::vars(['_auth', 'logged_in']);