<?php

namespace Core\Auth;

use Core\Db\DatabaseTable;
use Hindbiswas\QueBee\Table\CreateTable;

class TokenTable extends DatabaseTable
{
    public function __construct(
        \mysqli $conn,
        CreateTable $table,
        public readonly string $selector = 'selector',
        public readonly string $validator = 'hashed_validator',
        public readonly string $foreign_target = 'user_id',
        public readonly string $expiry = 'expiry',
    ) {
        parent::__construct($conn, $table);
        if (!$table->hasColumn($selector))
            throw new \Exception('Provided auth table does not the have given selector as column');
        if (!$table->hasColumn($validator))
            throw new \Exception('Provided auth table does not the have given validator as column');
        if (!$table->hasColumn($foreign_target))
            throw new \Exception('Provided auth table does not the have given foreign target as column');
        if (!$table->hasColumn($expiry))
            throw new \Exception('Provided auth table does not the have given expiry as column');
    }
}
