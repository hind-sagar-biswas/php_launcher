<?php

function d($data, string $header = 'DUMP'): void
{
    if (APP_DEBUG !== 'true') return;

    echo '<pre class="overflow-x-auto rounded-lg" style="background: #111111; border-radius: 3px; color: #efefef; padding: 10px;">';
    echo "\$_$header: " . PHP_EOL;
    echo var_export($data, true);
    echo '</pre>';
}
function dd($data): void
{
    d($data, 'DIE_DUMP');
    exit();
}
