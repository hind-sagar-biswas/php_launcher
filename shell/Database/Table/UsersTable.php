<?php

namespace Shell\Database\Table;

use Core\Auth\AuthTable;
use Core\Db\DB;
use Hindbiswas\QueBee\Col;
use Hindbiswas\QueBee\Table;
use Hindbiswas\QueBee\Table\CreateTable;
use Hindbiswas\QueBee\Table\Values\DefaultVal;

class UsersTable extends AuthTable
{
    public function __construct()
    {
        parent::__construct(DB::mysqli(), $this->table_query());
    }

    public function table_query(): CreateTable
    {
        $className = preg_replace('/Table$/', '', (new \ReflectionClass($this))->getShortName());
        $tableName = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $className));
        return Table::create($tableName)->columns([
            'id' => Col::integer(11)->unsigned()->pk()->ai(),
            'uid' => Col::varchar(10)->unique(),
            'username' => Col::varchar(225)->unique()->default(DefaultVal::NULL),
            'phone' => Col::varchar(100)->unique(),
            'email' => Col::varchar(100)->unique(),
            'is_superadmin' => Col::integer(1)->default('0'),
            'password' => Col::varchar(225),
            'update_date' => Col::dateTime()->setOnUpdate()->default(DefaultVal::CURRENT_TIME),
            'create_date' => Col::dateTime()->default(DefaultVal::CURRENT_TIME),
        ]);
    }
}