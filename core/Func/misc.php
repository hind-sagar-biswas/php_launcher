<?php

function listTables()
{
    $folderPath = ROOTPATH . 'shell/Database/Table/';
    $files = scandir($folderPath);
    $tableNames = [];

    // Filter out non-file entries and match table names
    $files = array_filter($files, function ($file) {
        return preg_match('/^(.*)Table\.php$/', $file);
    });

    // Sort files based on creation time
    usort($files, function ($a, $b) use ($folderPath) {
        $fileA = $folderPath . '/' . $a;
        $fileB = $folderPath . '/' . $b;
        return filectime($fileA) - filectime($fileB);
    });

    foreach ($files as $file) {
        preg_match('/^(.*)Table\.php$/', $file, $matches);
        $tableNames[] = $matches[1];
    }
    return $tableNames;
}
function pascalToUnderscore($pascalCase)
{
    $words = array_filter(preg_split('/(?=[A-Z])/', $pascalCase), fn ($v): bool => !empty($v));
    $underscored = strtolower(implode('_', $words));
    return $underscored;
}

function underscoreToPascalCase($underscored)
{
    $words = explode('_', $underscored);
    $camelCaseWords = array_map('ucfirst', $words);
    $pascalCase = implode('', $camelCaseWords);
    return $pascalCase;
}

function formatCurrency(?int $num): int|string
{
    if (!$num) return 0;
    if (abs($num) > 1000) {
        $x = round($num);
        $x_number_format = number_format($x);
        $x_array = explode(',', $x_number_format);
        $x_parts = array('K', 'M', 'B', 'T');
        $x_count_parts = count($x_array) - 1;
        $x_display = $x;
        $x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
        $x_display .= $x_parts[$x_count_parts - 1];
        return $x_display;
    }
    return $num;
}

function normalizePath($string)
{
    // Remove the slash at the beginning of the string if there is one.
    if (substr($string, 0, 1) === '/') {
        $string = substr($string, 1);
    }

    // Add a slash at the end of the string if there isn't one.
    if (!str_ends_with($string, '/') && !empty($string)) {
        $string = $string . '/';
    }

    return $string;
}
