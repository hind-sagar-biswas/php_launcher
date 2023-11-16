<?php

namespace Core\Base;

use Core\Security\Response;
use Exception;

class ExceptionHandler
{
    public static function handle(Exception $e)
    {
        if (APP_DEBUG) self::debug_mode($e);
        else self::non_debug_mode($e);
    }

    private static function non_debug_mode(Exception $e): void
    {
        // these are our templates
        $traceline = "\t\t\t#%s %s(%s): %s(%s)";
        $msg = "\n\tPHP Fatal error:  Uncaught exception '%s' with message '%s' in %s:%s\n\t\tStack trace:\n%s  thrown in %s on line %s";

        // alter traceif needed
        $trace = $e->getTrace();
        foreach ($trace as $key => $stackPoint) {
            // Check if 'args' key is set
            $args = isset($stackPoint['args']) ? $stackPoint['args'] : [];

            // (prevents passwords from ever getting logged as anything other than 'string')
            $trace[$key]['args'] = array_map('gettype', $args);
        }


        // build tracelines
        $result = array();
        foreach ($trace as $key => $stackPoint) {
            $result[] = sprintf(
                $traceline,
                $key,
                $stackPoint['file'],
                $stackPoint['line'],
                $stackPoint['function'],
                implode(', ', $stackPoint['args'])
            );
        }
        // trace ends with {main}
        $result[] = "\t\t\t#" . ++$key . " {main}";

        // write tracelines into main template
        $msg = sprintf(
            $msg,
            get_class($e),
            $e->getMessage(),
            $e->getFile(),
            $e->getLine(),
            implode("\n", $result),
            $e->getFile(),
            $e->getLine()
        );

        $log = new Log(ROOTPATH . 'server/logs', 'errors');
        $log->to_file($msg,  get_class($e));
        Response::terminateInternalServerError();
    }

    private static function debug_mode(Exception $e): void
    {
        $core_dir = realpath(__DIR__ . '/../');

        // building tracelines
        $trace = $e->getTrace();
        foreach ($trace as $key => $stackPoint) {
            // Check if 'args' key is set
            $args = isset($stackPoint['args']) ? $stackPoint['args'] : [];
            // (prevents passwords from ever getting logged as anything other than 'string')
            $trace[$key]['args'] = array_map('gettype', $args);
        }

        $result = [];
        foreach ($trace as $key => $stackPoint) {
            $absoluteFilePath = realpath($stackPoint['file']);
            if ($absoluteFilePath !== false && strpos($absoluteFilePath, $core_dir) === 0) {
                // The file is in or under the core directory.
                $result[] = [
                    '#' => ['text' => $key, 'style' => 'color:#95a5a6;'],
                    'file' => ['text' => $stackPoint['file'], 'style' => 'color:#95a5a6'],
                    'line' => ['text' => $stackPoint['line'], 'style' => 'color:#95a5a6'],
                    'function' => [
                        'text' => $stackPoint['function'] . "<span style='color:#95a5a6'>(" . implode(', ', $stackPoint['args']) . ")</span>",
                        'style' => ''
                    ],
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
            'file' => ['text' => $e->getFile(), 'style' => 'color:#98c379'],
            'line' => ['text' => $e->getLine(), 'style' => 'color:#d19a66'],
            'function' => ['text' => '{main}', 'style' => ''],
            'code' => ['text' => self::extract_error_line($e->getFile(), $e->getLine()), 'style' => ''],
        ];

        // write tracelines into main template
        $msg = self::to_table($result);

        // echo exception
        echo "<div style='background: #282c34; border-radius: 3px; color: #efefef; padding: 10px; font-family: monospace;'>
                <span style='border-left: 5px solid #ff0505; padding-left: 10px; font-size: 1.1rem;'>Uncaught [" . get_class($e) . "]: <i>" . $e->getMessage()   . "</i></span>
                <div class='debug-data' style='padding: 10px; overflow-x: auto;'>
                    $msg
                    </div>
                    <p style='border: 1px solid #333; padding: 5px 10px; margin: 5px 0 0 0;  background: #262626;'>
                        &gt;&gt; line <span style='color:#5c6370;'>[int]:</span><span style='color:#d19a66'> " . $e->getLine() . "</span>, file: <span style='color:#5c6370;'>[str]:</span><span style='color:#98c379'> " . $e->getFile() . "</span></p>
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
