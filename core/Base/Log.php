<?php

namespace Core\Base;

class Log
{
    public function __construct(
        public string $dir,
        public string $name,
        public string $file_type = 'txt',
        public bool $date_time = true,
        public bool $file_by_month = true,
    ) {
    }

    public function to_file($data, ?string $msg = null)
    {
        $data = var_export($data, true);
        $date = date('Y-m-d H:m:s');

        $file = $this->name;
        if ($this->file_by_month) $file .= DIRECTORY_SEPARATOR . date('Y-m');
        $file = $this->dir . DIRECTORY_SEPARATOR . $file . '.' . $this->file_type;

        $content = ($this->date_time) ? "$date " : '';
        $content .= ($msg) ? "[$msg] => " : '=> ';
        $content .= $data . PHP_EOL;

        $arr = explode(DIRECTORY_SEPARATOR, $file);
        array_pop($arr);
        $path = implode(DIRECTORY_SEPARATOR, $arr);

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        file_put_contents($file, $content, FILE_APPEND);
    }
}
