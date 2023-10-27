<?php

use Core\Base\RequestType;

class Echoable
{
    public static array $color = [
        'str' => '#98c379',   // green
        'int' => '#d19a66',   // Orange
        'float' => '#d4c07b', // Yellow 
        'bool' => '#4faeee',  // Blue
        'null' => '#95a5a6',  // Light Gray
        'def' => '#5c6370',   // Light Gray (for other types)
    ];

    public static function int($data): string
    {
        return "<span style='color:" . self::$color['def'] . ";'>[int]:</span><span style='color:" . self::$color['int'] . "'> $data</span>";
    }
    public static function float($data): string
    {
        return "<span style='color:" . self::$color['def'] . ";'>[float]:</span><span style='color:" . self::$color['float'] . "'> $data</span>";
    }
    public static function str($data): string
    {
        return "<span style='color:" . self::$color['def'] . ";'>[str]:</span><span style='color:" . self::$color['str'] . "'> $data</span>";
    }
    public static function bool($data): string
    {
        return "<span style='color:" . self::$color['def'] . ";'>[bool]:</span><span style='color:" . self::$color['bool'] . "'> " . var_export($data, true) . "</span>";
    }
    public static function null($data): string
    {
        return "<span style='color:" . self::$color['def'] . ";'>[??]:</span><span style='color:" . self::$color['null'] . "'> " . var_export($data, true) . "</span>";
    }
    public static function arr($data, $depth): string
    {
        $parsed = '';
        foreach ($data as $key => $value) {
            $parsed .= '<br>';
            for ($i=0; $i <= $depth; $i++) { 
                $parsed .= '&nbsp;&nbsp;&nbsp;&nbsp;';
            }
            $parsed .= "'$key' => " . self::parse($value, $depth + 1);
        }
        return "<span style='color:" . self::$color['def'] . ";'>[arr]:</span>$parsed";
    }
    public static function obj($data, $depth): string
    {
        $parsed = '';
        foreach ($data as $key => $value) {
            $parsed .= '<br>';
            for ($i=0; $i <= $depth; $i++) { 
                $parsed .= '&nbsp;&nbsp;&nbsp;&nbsp;';
            }
            $parsed .= "'$key' => " . self::parse($value, $depth + 1);
        }
        return "<span style='color:" . self::$color['def'] . ";'>[obj]:</span> " . get_class($data) . "$parsed";
    }
    public static function parse($data, $depth = 0): string
    {
        if (is_int($data)) $parsed = self::int($data);
        elseif (is_float($data)) $parsed = self::float($data);
        elseif (is_string($data)) $parsed = self::str($data);
        elseif (is_bool($data)) $parsed = self::bool($data);
        elseif (is_null($data)) $parsed = self::null($data);
        elseif (is_array($data)) $parsed = self::arr($data, $depth);
        elseif (is_object($data)) $parsed = self::obj($data, $depth);
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

        echo '<pre class="overflow-x-auto rounded-lg" style="background: #282c34; border-radius: 3px; color: #efefef; padding: 10px;">';
        echo PHP_EOL . "<span style='border-left: 5px solid #ad68d8; padding-left: 10px; font-size: 1.1rem;'>" . "\$_$header : " . "</span>";
        echo PHP_EOL . "<div style='padding: 10px;'>" . Echoable::parse($data) . "</div>";
        echo "<p style='border: 1px solid #333; padding: 5px 10px; margin: 0;  background: #262626'>";
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
