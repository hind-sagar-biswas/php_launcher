<?php

namespace Core\Base;

class Export
{
    public static function vars(array $variableNames)
    {
        $GLOBALS['__exported'] = implode('|', $variableNames);
    }
    public static function consts(array $variableNames)
    {
        foreach ($variableNames as $var) {
            define('_' . strtoupper($var), $GLOBALS[$var]);
        }
    }
}
