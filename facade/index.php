<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>





    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-dark.min.css">
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>

    <!-- and it's easy to individually load additional languages -->
    <!-- <script defer src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/go.min.js"></script> -->


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
        <h4 style="max-width: 800px; width: 100%; margin: auto;"><pre><?php include ROOTPATH . 'core/Cmd/info.php' ?></pre></h4>

        <h1 style="text-align: center;">Welcome to Homepage</h1>

        <?php d(REQUEST) ?>
        <hr>
        <div class="table-of-contents">
            <h3># Table of Contents</h3>
            <ol>
                <a href="#doc_declare_routes">
                    <li>Declare Custom Routes</li>
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
        <div>
            <h2 id="doc_declare_routes">Declare custom routes:</h2>

            <h3>Step 1: Enable custom router</h3>
            <p>To enable controled/custom routes insted of filesystem, go to <code class="code">/shell/.nev</code> and change the value of <code class="code">ROUTER_TYPE</code> to the following</p>
            <pre><code class="lamguage-env">ROUTER_TYPE=custom</code></pre>

            <h3>Step 2: Create routes</h3>
            <p>To declare routes go to <code class="code">/shell/routes/</code>. In there, there are 2 files i.e <code class="code">web.php</code> [that controlls norma routes where response is html] and <code class="code">api.php</code> [which controlls api routes for urls starting with <code class="code">/api/</code> and response type is JSON]. The file content looks like this:</p>

            <pre><code class="lamguage-php">&lt;?php

use Core\Router\Router;

$Router->add_routes(
    Router::get('/')->name('home')->call('index'),
);</code></pre>
            <p>There, use <code class="code">Router::get('/route/path/')->name('route.name')->call('file/name')</code> format to declare new routes</p>
        </div>

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