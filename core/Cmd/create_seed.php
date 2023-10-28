<?php

use Hindbiswas\Phpdotenv\StdIO;

function create_seed($tableName)
{
    include_once ROOTPATH . 'shell\Database\list.php';
    if (!in_array($tableName, $DATABASE_TABLE_LIST)) {
        StdIO::put("❌ '$tableName' not present in `\$DATABASE_TABLE_LIST`.");
        return;
    }
    if (!file_exists(ROOTPATH . 'shell/Database/Table/' . underscoreToPascalCase($tableName) . 'Table.php')) {
        StdIO::put("🟡 Table cass for '$tableName'not found.");
        $create = StdIO::get(StdIO::red('!') . ' Create table?', 'y', ['y', 'n']);
        if ($create === 'y') {
            include_once ROOTPATH . 'core\Cmd\create_table.php';
            create_table($tableName);
        } else {
            StdIO::put(StdIO::yellow('Action Aborted...'));
            return;
        }
    }

    $seederName = underscoreToPascalCase($tableName) . 'Seed';
    $directory = ROOTPATH . 'shell/Database/Seed/';

    $filePath = $directory . $seederName . '.php';

    if (file_exists($filePath)) {
        StdIO::put("🟡 Seeder class for '$seederName' already exists.");
        return;
    }

    $template = <<<EOD
<?php

namespace Shell\Database\Seed;

use Core\Db\TableSeeder;
use Core\Security\Random;

class $seederName implements TableSeeder
{
    public function seeds(): array
    {
        \$seeds = [];

        return \$seeds;
    }
}

EOD;

    // Create the table class file
    file_put_contents($filePath, $template);

    StdIO::put("✅ Table class for '$seederName' created successfully.");
}
