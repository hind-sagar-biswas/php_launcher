<?php



function create_table($tableName)
{
    $tableName = underscoreToPascalCase($tableName) . 'Table';
    $directory = ROOTPATH . 'shell/Database/Table/';

    $filePath = $directory . $tableName . '.php';

    if (file_exists($filePath)) {
        put("🟡 Table class for '$tableName' already exists.\n");
        return;
    }

    $template = <<<EOD
<?php

namespace Shell\Database\Table;

use Core\Db\DatabaseTable;
use Core\Db\DB;
use Hindbiswas\QueBee\Col;
use Hindbiswas\QueBee\Table;
use Hindbiswas\QueBee\Table\CreateTable;
use Hindbiswas\QueBee\Table\Values\DefaultVal;

class $tableName extends DatabaseTable
{
    public function __construct()
    {
        parent::__construct(DB::mysqli(), \$this->table_query());
    }

    public function table_query(): CreateTable
    {
        \$className = preg_replace('/Table$/', '', (new \ReflectionClass(\$this))->getShortName());
        \$tableName = strtolower(preg_replace('/([a-z])([A-Z])/', '\$1_\$2', \$className));
        return Table::create(\$tableName)->columns([
            'id' => Col::integer(11)->unsigned()->pk()->ai(),
        ]);
    }
}
EOD;

    // Create the table class file
    file_put_contents($filePath, $template);

    put("✅ Table class for '$tableName' created successfully.\n");
}