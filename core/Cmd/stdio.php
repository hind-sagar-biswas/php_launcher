<?php

function ask($prompt, $default = null)
{
    $default_show = ($default) ? "[$default]" : '';
    $input = readline("$prompt $default_show: ");
    return (trim($input) == "") ? $default : $input;
}

function put($text, int $multiplier = 1, bool $return = false)
{
    $output_text = '';
    for ($i = 0; $i < $multiplier; $i++) {
        $output_text = $output_text . $text;
    }
    if ($return) {
        return $output_text;
    }
    echo $output_text . PHP_EOL;
}
