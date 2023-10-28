<?php

use Core\Router\RouteSystem; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?></title>

    <link rel="shortcut icon" href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAAAXNSR0IArs4c6QAAA5NJREFUWEfVl3lQTVEcx7/npUULekSRQRtZstS02dqszwgxiLEVmmRozJgpRRjGMsyUyYT+4MWMmFHR2EfWkWVoQoZSWvSKSr3y6vXeu8fca1we6t480xvnz9855/v93N/53d+9hyQhSaL2dYonDIkGgQO6Y1AoqIQeNc8v3UvifOUJhJLd3eH7qwclNJHE+WQoCGBvDAAAChLvk0GNZM7Z/n8AZhY9MGRcfwxw7gPLXmYABVRKNerKlSgv/ITW5vYuJVR0Bvo62mDKilHwmD4ULMSfhk7LoOhOJW6mFaCuslkUiCgA1jhk7ViYmEpEiWratDiX9ABFtysF1wsCBKwejWnrx4FS4N0TBV7lVaCqqB7KOhUoA9j06wlnzwGYtGwketlZ8oY6DYO0yKuoftPQKUSnAOaWpth6aQFK8quRd/Ilaoo/g0gIvEJdOBBVo5oXt+pjjqj0mZA62vCxtw+rcSr21t8D/GnnggQ/eM5xRnr0DZQ9q9VbMkHmhLBEfz7GaBnsCs6ERq3rEELwCH7e6T7ZEcsPBnChY2uvoeLFJz1he1dbbMyQ6cUOL8xBfVXHBdklgI2nZbB3seUMDoVlo+FDi56Zg5stYuT6AAdCL6CpVmV4Bpw87RGRGsIJadt12Bl4FoxOv4mOChiM8H1TebNWZTv2zDgPylZwB0N0Bhbu8Mf4WU6cDFvZqSsv/yYZluiHCTJnPv4kuxjZ+x4ZXoTs+x9/dREsrEw5sacXS5C1N19PmM3Q6pQgSEy+9Qq1SoPk8Fw01XwxHMDVxwGrkoN5oZz9j/A4qxhWthYYOFyK4RMHwXueK9+oGIbi7La7eJX3DxoR6zp7kycmLnXnAdj0u09xRFCEx29Px/aGC3se4vW9qk6f/PukqBqIkc+Gg5uUL8BdQZmwllpg8vKRsBvWG6ZmJmiub0XZ848ouFKKthaNKHN2kSCAmWUPJN5cDImEcKK1pY1ICc8VbSC0UBBgiIcd1h2fweu8L/iIE1HXhXRFzwsCeM11wfx4X17wzYMPkG/JE20gtFAQIDjSA0GRP4qt8Pp7ZG6/L6Qrel4QQBbrBf/FI7rUXES7iylC2WYv+C/5AXD/TBGuHHnWFQ/DGlHgmjEIWTeWF2HTzx7DvxqCRyAdZA3v+W7cz2bZ81pUFOp/gg0FEQQw1EBov/EB4nzkCgJilKsZpbTG+JfT79dzUGzorkxQ0BoQpLLX868VMGqyT8FvvAAAAABJRU5ErkJggg==" type="image/png">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-dark.min.css">
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            background-color: #eee;
            padding: 0;
            margin: 0;
        }

        .doc {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #fff;
            padding: 10px;
            width: 100svw;
            max-width: 1200px;
            margin: 0 auto;
        }

        .doc h1,
        .doc h2,
        .doc h3,
        .doc h4,
        .doc h5,
        .doc h6 {
            color: #222;
        }

        .doc h2::before {
            content: '# ';
            color: darkmagenta;
        }

        .doc>div {
            margin-top: 20px;
            margin-bottom: 20px;
            background-color: #fefefe;
        }

        .doc .code {
            background-color: #eee;
            color: darkmagenta;
            padding: 1px 5px;
            border-radius: 3px;
        }

        .doc .table-of-contents {
            border: 2px solid #0983ed;
            border-radius: 5px;
            padding: 10px;
        }

        .doc .table-of-contents h3 {
            padding: 10px;
            margin: 0;
            color: #064d9e;
        }

        .doc .table-of-contents ol {
            margin: 5px 0;
        }

        .doc .table-of-contents ol a {
            text-decoration: none;
            color: #0983ed;
        }

        .doc .table-of-contents ol a:hover {
            color: #064d9e;
        }
    </style>
</head>

<body>

    <div class="doc">
        <?php include_once ROOTPATH . 'core/Cmd/stdio.php' ?>
        <h4 style="max-width: 800px; width: 100%; margin: auto;">
            <pre><?php include ROOTPATH . 'core/Cmd/info.php' ?></pre>
        </h4>

        <h1 style="text-align: center;">Welcome to Homepage</h1>

        <?php d(REQUEST) ?>
        <hr>
        <div class="table-of-contents">
            <h3># Table of Contents</h3>
            <ol>
                <a href="#doc_declare_routes">
                    <?php if (APP_ROUTE_SYS === RouteSystem::RAW) : ?>
                        <li>Declare Custom Routes</li>
                    <?php else : ?>
                        <li>Move to Raw Routes</li>
                    <?php endif; ?>
                </a>
                <a href="#doc_debugging">
                    <li>Debugging</li>
                </a>
                <a href="#doc_include_static">
                    <li>Including Static Files</li>
                </a>
            </ol>
        </div>
        <hr>


        <?php if (APP_ROUTE_SYS === RouteSystem::RAW) : ?>
            <div>
                <h2 id="doc_declare_routes">Declare custom routes:</h2>

                <h3>Step 1: Enable custom router</h3>
                <p>To enable controled/custom routes insted of filesystem, go to <code class="code">/shell/.env</code> and change the value of <code class="code">APP_ROUTE_SYSTEM</code> to the following</p>
                <pre><code class="lamguage-env">APP_ROUTE_SYSTEM=controlled</code></pre>

                <h3>Step 2: Create routes</h3>
                <p>To declare routes go to <code class="code">/shell/routes/</code>. In there, there are 2 files i.e <code class="code">web.php</code> [that controlls norma routes where response is html] and <code class="code">api.php</code> [which controlls api routes for urls starting with <code class="code">/api/</code> and response type is JSON]. The file content looks like this:</p>

                <pre><code class="lamguage-php">&lt;?php

use Core\Router\Router;

$Router->add_routes(
    Router::get('/')->name('home')->call('index'),
);</code></pre>
                <p>There, use <code class="code">Router::get('/route/path/')->name('route.name')->call('file/name')</code> format to declare new routes</p>
            </div>
        <?php else : ?>
            <div>
                <h2 id="doc_declare_routes">Move to raw routes:</h2>

                <h3>Step 1: Enable raw router</h3>
                <p>To enable raw/filesystem based routes insted of declarative and controlled ones, go to <code class="code">/shell/.env</code> and change the value of <code class="code">APP_ROUTE_SYSTEM</code> to the following</p>
                <pre><code class="lamguage-env">APP_ROUTE_SYSTEM=raw</code></pre>

                <p>Now the routes will follow the name of the file, so <code class="code">url/path/to/filename/</code> will output the contents of <code class="code">./facade/path/to/filename.php</code></p>
            </div>
        <?php endif; ?>

        <div>
            <h2 id="doc_debugging">Debugging</h2>

            <h3>Dump</h3>
            <p>To dump variables, use <code class="code">d()</code> function</p>

            <h3>Die Dump</h3>
            <p>To dump variables and stop execution, use <code class="code">dd()</code> function</p>
        </div>

        <div>
            <h2 id="doc_include_static">Including static files</h2>
            <p>Static files are kept in either <code class="code">./assets/</code> or <code class="code">./node_modules/</code> directory. CSS, JS and Images are in respectively <code class="code">/css/</code>, <code class="code">/js/</code> and <code class="code">/images/</code> directory inside <code class="code">./assets/</code></p>

            <h3>1. Images</h3>
            <p>Use <code class="code">_image()</code> function to get the path</p>
            <pre><code class="lamguage-php">&lt;img src="&lt;?= _image('filename.extension') ?&gt;" alt=""&gt;

&lt;!-- Output --&gt;
&lt;!-- &lt;img src="http://url/assets/images/filename.extension" alt=""&gt; --&gt;</code></pre>

            <h3>2. CSS</h3>
            <p>use <code class="code">_css()</code> function to get the css inclusion code</p>
            <pre><code class="lamguage-php">&lt;?php _css('filename');

// Output:
// &lt;link rel="stylesheet" href="http://url/assets/css/filename.css"&gt;</code></pre>

            <h3>3. JS</h3>
            <p>use <code class="code">_js()</code> function to get the js inclusion code</p>
            <pre><code class="lamguage-php">&lt;?php _js('filename');

// Output:
// &lt;script defer src="http://url/assets/js/filename.js"&gt;&lt;/script&gt;</code></pre>

            <h3>4. [node_module] CSS</h3>
            <p>use <code class="code">_node_css()</code> function to get the css inclusion code</p>
            <pre><code class="lamguage-php">&lt;?php _node_css('path/to/filename.extension');

// Output:
// &lt;link rel="stylesheet" href="http://url/node_modules/path/to/filename.extension"&gt;</code></pre>

            <h3>4. [node_module] JS</h3>
            <p>use <code class="code">_node_js()</code> function to get the js inclusion code</p>
            <pre><code class="lamguage-php">&lt;?php _node_js('path/to/filename.extension');

// Output:
// &lt;script defer src="http://url/node_modules/path/to/filename.extension"&gt;&lt;/script&gt;</code></pre>
        </div>
    </div>

    <script defer>
        window.addEventListener('load', () => {
            hljs.highlightAll();
        });
    </script>
</body>

</html>