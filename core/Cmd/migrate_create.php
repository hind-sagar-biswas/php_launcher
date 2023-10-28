<?php


function migrate_create()
{
    include_once ROOTPATH . 'shell\Database\list.php';
    include_once ROOTPATH . 'core\Cmd\create_table.php';

    foreach ($DATABASE_TABLE_LIST as $tableName) {
        create_table($tableName);
    }
}
