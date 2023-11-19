<?php

use Core\Db\DB;
use Hindbiswas\Phpdotenv\StdIO;

function add_to_migrated_list(string $table, int &$index) {
    $path = ROOTPATH . 'server/internal/';
    if (!file_exists($path)) {
        mkdir($path, 0777, true);
    }
    $file = $path . 'migrations.txt';
    if ($index < 1) file_put_contents($file, $table . PHP_EOL);
    else file_put_contents($file, $table . PHP_EOL, FILE_APPEND);
    $index++;
}

function retrieve_migrated_tables()
{
    $file = ROOTPATH . 'server/internal/migrations.txt';
    if (file_exists($file)) {
        $migratedTables = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        return $migratedTables;
    }
    return [];
}

function migrate_flush()
{
    $DATABASE_TABLE_LIST = listTables();
    $RETRIVED_TABLE_LIST = retrieve_migrated_tables();

    $methodName = 'table_query';
    $index = 0;

    $db = DB::mysqli();
    foreach ($RETRIVED_TABLE_LIST as $table) {
        $db->query("SET FOREIGN_KEY_CHECKS = 0;");
        $db->query("DROP TABLE IF EXISTS `" . pascalToUnderscore($table) . "`;");
        $db->query("SET FOREIGN_KEY_CHECKS = 1;");
    }
    $db->close();

    foreach ($DATABASE_TABLE_LIST as $table) {
        $tableName = $table . 'Table';
        $className = "\\Shell\\Database\\Table\\$tableName";

        if (class_exists($className)) {
            $instance = new $className();

            if (method_exists($instance, $methodName)) {
                $query = $instance->$methodName();
                $instance->flush(true);
                $instance->run($query->build());

                add_to_migrated_list($table, $index);
                StdIO::put("✅ `" . StdIO::yellow($query->name) . "` table created successfully.");
            } else {
                StdIO::put("❌ `" . StdIO::yellow($methodName) . "` method does not exist in class " . StdIO::yellow($className) . ".");
            }
        } else {
            StdIO::put("❌ `" . StdIO::yellow($className) . "` does not exist.");
        }
    }
}
