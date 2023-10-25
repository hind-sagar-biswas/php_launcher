<?php

namespace Core\Auth;

use Core\Db\DatabaseTable;
use Hindbiswas\QueBee\Table\CreateTable;

class AuthTable extends DatabaseTable
{
    public function __construct(
        \mysqli $conn,
        CreateTable $table,
        public readonly string $identifier = 'username',
        public readonly string $key = 'password'
    ) {
        parent::__construct($conn, $table);
        if (!$table->hasColumn($identifier))
            throw new \Exception('Provided auth table does not have give identifier as column');
        if (!$table->hasColumn($key))
            throw new \Exception('Provided auth table does not have give key as column');
    }
}
