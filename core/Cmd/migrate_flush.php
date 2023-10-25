<?php

use Hindbiswas\Phpdotenv\StdIO;

function migrate_flush()
{
    include_once ROOTPATH . 'shell\Database\list.php';
    $methodName = 'table_query';

    foreach ($DATABASE_TABLE_LIST as $tableName) {
        $tableName = underscoreToPascalCase($tableName) . 'Table';
        $className = "\\Shell\\Database\\Table\\$tableName";

        if (class_exists($className)) {
            $instance = new $className();

            if (method_exists($instance, $methodName)) {
                $query = $instance->$methodName();
                $instance->flush(true);
                $instance->run($query->build());
                StdIO::put("✅ `" . StdIO::yellow($query->name) . "` table created successfully.");
            } else {
                StdIO::put("❌ `" . StdIO::yellow($methodName) . "` method does not exist in class " . StdIO::yellow($className) . ".");
            }
        } else {
            StdIO::put("❌ `" . StdIO::yellow($className) . "` does not exist.");
        }
    }
}
