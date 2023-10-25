<?php

use Core\Base\RequestType;

class Echoable
{
    public static array $color = [
        'str' => '#e74c3c',   // Red
        'int' => '#f39c12',   // Orange
        'float' => '#198754', // Green 
        'bool' => '#3498db',  // Blue
        'null' => '#95a5a6',  // Light Gray
        'def' => '#95a5a6',   // Light Gray (for other types)
    ];

    public static function int($data): string
    {
        return "[int]:<span style='color:" . self::$color['int'] . "'> $data</span>";
    }
    public static function float($data): string
    {
        return "[float]:<span style='color:" . self::$color['float'] . "'> $data</span>";
    }
    public static function str($data): string
    {
        return "[str]:<span style='color:" . self::$color['str'] . "'> $data</span>";
    }
    public static function bool($data): string
    {
        return "[bool]:<span style='color:" . self::$color['bool'] . "'> " . var_export($data, true) . "</span>";
    }
    public static function null($data): string
    {
        return "[??]:<span style='color:" . self::$color['null'] . "'> " . var_export($data, true) . "</span>";
    }
    public static function parse($data): string
    {
        if (is_int($data)) $parsed = self::int($data);
        elseif (is_float($data)) $parsed = self::float($data);
        elseif (is_string($data)) $parsed = self::str($data);
        elseif (is_bool($data)) $parsed = self::bool($data);
        elseif (is_null($data)) $parsed = self::null($data);
        else $parsed = var_export($data, true);
        return $parsed;
    }
}

function getCallLocation()
{
    $trace = debug_backtrace();
    return [$trace[1]['line'], $trace[1]['file']];
}

function d($data, string $header = 'DUMP'): void
{
    if (APP_DEBUG !== 'true') return;
    if (REQUEST->type === RequestType::WEB) {

        [$line, $file] = getCallLocation();

        echo '<pre class="overflow-x-auto rounded-lg" style="background: #111111; border-radius: 3px; color: #efefef; padding: 10px;">';
        echo PHP_EOL . "<span style='border-left: 5px solid purple; padding-left: 10px; font-size: 1.1rem;'>" . "\$_$header : " . "</span>";
        echo PHP_EOL . "<div style='padding: 10px;'>" . Echoable::parse($data) . "</div>";
        echo "<p style='border: 1px solid black; padding: 5px 10px; margin: 0;  background: #090909'>";
        echo ">> line " . Echoable::int($line) . ", file: " . Echoable::str($file) . "</p>";
        echo '</pre>';
    } else {
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
}
function dd($data): void
{
    d($data, 'DIE_DUMP');
    exit();
}
