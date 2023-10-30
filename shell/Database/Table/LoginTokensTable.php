<?php

namespace Shell\Database\Table;

use Core\Auth\TokenTable;
use Core\Db\DB;
use Hindbiswas\QueBee\Col;
use Hindbiswas\QueBee\Table;
use Hindbiswas\QueBee\Table\CreateTable;
use Hindbiswas\QueBee\Table\Values\FK;

class LoginTokensTable extends TokenTable
{
    public function __construct()
    {
        parent::__construct(DB::mysqli(), $this->table_query());
    }

    public function table_query(): CreateTable
    {
        $authTable = new UsersTable();

        $className = preg_replace('/Table$/', '', (new \ReflectionClass($this))->getShortName());
        $tableName = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $className));
        return Table::create($tableName)->columns([
            'id' => Col::integer(11)->unsigned()->pk()->ai(),
            'selector' => Col::varchar(225),
            'hashed_validator' => Col::varchar(225),
            'user_id' => Col::integer(11)->unsigned(),
            'expiry' => Col::dateTime(),
        ])->foreign('user_id')->onDelete(FK::CASCADE)->reference($authTable->table, 'id');
    }
}