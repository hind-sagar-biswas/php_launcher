<?php

namespace Core\Base;

use Exception;

class ExceptionHandler
{
    public static function handle(Exception $exception)
    {
        $core_dir = realpath(__DIR__ . '/../');

        // building tracelines
        $trace = $exception->getTrace();
        $result = [];
        foreach ($trace as $key => $stackPoint) {
            $absoluteFilePath = realpath($stackPoint['file']);
            if ($absoluteFilePath !== false && strpos($absoluteFilePath, $core_dir) === 0) {
                // The file is in or under the core directory.
                $result[] = [
                    '#' => ['text' => $key, 'style' => 'color:#95a5a6;'],
                    'file' => ['text' => $stackPoint['file'], 'style' => 'color:#95a5a6'],
                    'line' => ['text' => $stackPoint['line'], 'style' => 'color:#95a5a6'],
                    'function' => ['text' => $stackPoint['function'], 'style' => 'color:#95a5a6'],
                    'code' => ['text' => 'internal code', 'style' => 'color:#d19a66'],
                ];
            } else {
                // The file is not in or under the core directory.
                $result[] = [
                    '#' => ['text' => $key, 'style' => 'font-weight: bold;'],
                    'file' => ['text' => $stackPoint['file'], 'style' => 'color:#98c379'],
                    'line' => ['text' => $stackPoint['line'], 'style' => 'color:#d19a66'],
                    'function' => ['text' => $stackPoint['function'], 'style' => 'color:#4faeee'],
                    'code' => ['text' => self::extract_error_line($stackPoint['file'], $stackPoint['line']), 'style' => ''],
                ];
            }


        }
        // trace always ends with {main}
        $result[] = [
            '#' => ['text' => ++$key, 'style' => 'font-weight: bold;'],
            'file' => ['text' => $exception->getFile(), 'style' => 'color:#98c379'],
            'line' => ['text' => $exception->getLine(), 'style' => 'color:#d19a66'],
            'function' => ['text' => '{main}', 'style' => ''],
            'code' => ['text' => self::extract_error_line($exception->getFile(), $exception->getLine()), 'style' => ''],
        ];

        // write tracelines into main template
        $msg = self::to_table($result);

        // echo exception
        echo "<div style='background: #282c34; border-radius: 3px; color: #efefef; padding: 10px; font-family: monospace;'>
                <span style='border-left: 5px solid #ff0505; padding-left: 10px; font-size: 1.1rem;'>Uncaught Exception : " . $exception->getMessage()   . "</span>
                <div class='debug-data' style='padding: 10px; overflow-x: auto;'>
                    $msg
                    </div>
                    <p style='border: 1px solid #333; padding: 5px 10px; margin: 5px 0 0 0;  background: #262626;'>
                        &gt;&gt; line <span style='color:#5c6370;'>[int]:</span><span style='color:#d19a66'> " . $exception->getLine() . "</span>, file: <span style='color:#5c6370;'>[str]:</span><span style='color:#98c379'> " . $exception->getFile() . "</span></p>
            </div>";
    }

    private static function extract_error_line($file_path, $line_number)
    {
        $file_lines = file($file_path);
        if (isset($file_lines[$line_number - 1])) return $file_lines[$line_number - 1];
        return "Error: Line $line_number does not exist in the file.";
    }

    private static function to_table(array $data)
    {
        if (empty($data)) {
            return '<p>No data to display.</p>';
        }

        $html = '<table border="1" cellpadding="10px" width="100%"><tr style="background-color: #262626;">';

        // Extract column headers
        $headers = array_keys(current($data));
        foreach ($headers as $header) {
            $html .= "<th>$header</th>";
        }

        $html .= '</tr>';

        foreach ($data as $row) {
            $html .= '<tr>';
            foreach ($row as $column => $columnData) {
                $text = $columnData['text'] ?? '';
                $class = $columnData['class'] ?? '';
                $style = $columnData['style'] ?? '';

                $html .= "<td class='$class' style='$style'>$text</td>";
            }
            $html .= '</tr>';
        }

        $html .= '</table>';

        return $html;
    }
}
