<?php

use Hindbiswas\Phpdotenv\StdIO;
use Hindbiswas\QueBee\Query;

function migrate_dump()
{
    $dateTime = date("Y-m-d_H-m-s");
    $dump = "-- PHP Launcher Migration Dump" . PHP_EOL;
    $dump .= "-- Author: Hind Sagar Biswas" . PHP_EOL;
    $dump .= "-- Dumped on $dateTime" . PHP_EOL;
    $dump .= "-- MySQL Database" . PHP_EOL;
    $dump .= PHP_EOL . "-- Table Dumps" . PHP_EOL;

    include_once ROOTPATH . 'shell\Database\list.php';
    $methodName = 'table_query';

    foreach ($DATABASE_TABLE_LIST as $tableName) {
        $tableName = underscoreToPascalCase($tableName) . 'Table';
        $className = "\\Shell\\Database\\Table\\$tableName";

        if (class_exists($className)) {
            $instance = new $className();

            if (method_exists($instance, $methodName)) {
                $query = $instance->$methodName();
                $dump .= PHP_EOL . "-- Table: " . $instance->table->name . PHP_EOL . $query->build() . PHP_EOL;
                StdIO::put("✅ `" . StdIO::yellow($query->name) . "` table dumped successfully.");
            } else {
                StdIO::put("❌ `" . StdIO::yellow($methodName) . "` method does not exist in class " . StdIO::yellow($className) . ".");
            }
        } else {
            StdIO::put("❌ `" . StdIO::yellow($className) . "` does not exist.");
        }
    }

    $dump .= PHP_EOL . "-- Table Seed Dumps" . PHP_EOL;
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
                $dump .= PHP_EOL . "-- Table: " . $instance->table->name . PHP_EOL  . Query::insertMultiple($seeds)->into($instance->table->name)->build() . PHP_EOL;

                StdIO::put("✅ `" . StdIO::yellow($tableName) . "` seed dumped successfully.");
            } else {
                StdIO::put("❌ `" . StdIO::yellow($methodName) . "` method does not exist in seeding class " . StdIO::yellow($className) . ".");
            }
        }
    }
    
    StdIO::put("Dumping to db_dump_$dateTime.sql file...");
    $file = __DIR__ . "\..\..\db_dump_$dateTime.sql";
    $fp = fopen($file, "w");
    fwrite($fp, $dump);
    fclose($fp);
    StdIO::put("✅ dump generated successfully.");
}
