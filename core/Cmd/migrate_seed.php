<?php

use Hindbiswas\Phpdotenv\StdIO;

function migrate_seed()
{
    include_once ROOTPATH . 'shell\Database\list.php';
    $methodName = 'seeds';

    foreach ($DATABASE_TABLE_LIST as $dbTableName) {
        $tableName = underscoreToPascalCase($dbTableName) . 'Table';
        $seedName = underscoreToPascalCase($dbTableName) . 'Seed';
        $className = "\\Shell\\Database\\Table\\$tableName";
        $seedClassName = "\\Shell\\Database\\Seed\\$seedName";

        if (class_exists($className) && class_exists($seedClassName)) {
            $instance = new $className();
            $seedInstance = new $seedClassName();

            if (method_exists($seedInstance, $methodName)) {
                $seeds = $seedInstance->$methodName();
                foreach ($seeds as $seed) {
                    try {
                        $instance->insert($seed);
                    } catch (\Throwable $th) {
                        StdIO::put(StdIO::red('-'), 60);
                        StdIO::put(StdIO::red('|| ERROR :'));
                        StdIO::put($th);
                        StdIO::put(StdIO::red('-'), 60);
                    }
                }
                StdIO::put("✅ `" . StdIO::yellow($tableName) . "` seeded successfully.");
            } else {
                StdIO::put("❌ `" . StdIO::yellow($methodName) . "` method does not exist in seeding class " . StdIO::yellow($className) . ".");
            }
        }
    }
}
