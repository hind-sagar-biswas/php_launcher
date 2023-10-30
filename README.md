# PHP Launcher

          ██████╗ ██╗  ██╗██████╗     ██╗      █████╗ ██╗   ██╗███╗   ██╗ ██████╗██╗  ██╗███████╗██████╗ 
          ██╔══██╗██║  ██║██╔══██╗    ██║     ██╔══██╗██║   ██║████╗  ██║██╔════╝██║  ██║██╔════╝██╔══██╗
          ██████╔╝███████║██████╔╝    ██║     ███████║██║   ██║██╔██╗ ██║██║     ███████║█████╗  ██████╔╝
          ██╔═══╝ ██╔══██║██╔═══╝     ██║     ██╔══██║██║   ██║██║╚██╗██║██║     ██╔══██║██╔══╝  ██╔══██╗
          ██║     ██║  ██║██║         ███████╗██║  ██║╚██████╔╝██║ ╚████║╚██████╗██║  ██║███████╗██║  ██║
          ╚═╝     ╚═╝  ╚═╝╚═╝         ╚══════╝╚═╝  ╚═╝ ╚═════╝ ╚═╝  ╚═══╝ ╚═════╝╚═╝  ╚═╝╚══════╝╚═╝  ╚═╝

It is a pseudo-framework for PHP developers to get a quick start with their projects with the highest control and lowest learning curve!

## Installing and getting started

### Step 1: Downloading/Cloning the code

Download the code of this repository or clone it *(if cloned, be sure to remove `.git` and `.github` folder)*. To clone run:

```
https://github.com/hind-sagar-biswas/php_launcher.git
```

### Step 2: Run the launcher

Rename the folder to your project and then enter it. Then run the follwing code. Be sure to have internet connection as it will install composer packages too. Then run the follwing command and it'll guide you through an easy installation process where you'll need to provide informations on the project.

```
php launch
```

Now you are all set up! You'll find more docs when you visit the homepage of the project without making any changes.

## Table of Contents
1. Declare Custom Routes
2. Move to Raw Routes
3. Debugging
4. Including Static Files
5. CSRF Protection

___

## Declare custom routes:

#### Step 1: Enable custom router

To enable controlled/custom routes insted of filesystem,go to `/shell/.ev` and change the value of ``APP_ROUTE_SYSTEM`` to the follwing

```shell
APP_ROUTE_SYSTEM=controlled
```
#### Step 2: Create routes

To declare routes go to ``/shell/routes/``.In there, there are 2 files i.e ``web.php``[that controlls norma routes where response is html]and ``api.php``[which controlls api routes for urls starting with ``/api/`` and response type is JSON].The files content looks like this:

```php
<?php

use Core/Router/Router;

$Router->add_routes(
    Router::get('/')->name('home')->call('index'),
);

```
There, use ``Router::get('/route/path/')-name('route.name')->call('file.name')`` format to declare new routes 

## Move to raw routes:

#### step 1: Enable raw router

To enable raw/filesystem based routes insted of declarative and controlled ones, go to ``/shell/.ev`` and change the value of  ``APP_ROUTE_SYSTEM`` to the following
```shell
APP_ROUTE_SYSTEM=raw
```
Now the routes will follow the name of the file,``url/path/to/filename/`` will output the contents of ``./facade/path/to/filename.php``

## Debugging

**Dump**

To dump variables, use ``d()`` function

**Die Dump**

To dump variables and stop execution, use ``dd()`` function

## Including static files

Static files are kept in either`` ./assets/`` or ``./node_modules/`` directory. CSS, JS and Images are in respectively ``/css/``,``/js/`` and ``/images/ ``directory inside ``./assets/``

**1.Images**

Use ``_image()`` function to get the path

```php
<img src="<?= _image('filename.extension') ?>" alt="">
<!-- output -->
<!-- <img src="href="http://url/assets/images/filename.extension" alt=""> -->
```

**2. CSS**

Use ``_css()`` function to get the css inclusion code

```php
<?php _css('filename');
// output:
//<link rel="stylesheet" href="http://url/assets/css/filename.css">


```

**3. JS**

Use ``_js()`` function to get the js inclusion code

```php
<?php _js('filename');
// output:
//<script defer src="http://url/assets/js/filename.js"></script>

```

**4. [node_module] CSS**

Use ``_node_css()`` function to get the css inclusion code

```php
<?php _node_css('path/to/filename.extension');
// output:
//<link rel="stylesheet" href="http://url/node_modules/path/to/filename.extension">

```

**5. [node_module] JS**

Use ``_node_js()`` function to get the js inclusion code

```php
<?php _node_js('path/to/filename.extension'); ?>
// output:
//<script defer src="http://url/node_modules/path/to/filename.extension"></script>

```

## CSRF Protection

**!! Enable Csrf**

First enable csrf from ``.env`` file and to do that , go to`` /shell/.env`` and change the value of ``CSRF_ENABLED`` to the following 
```php
CSRF_ENABLED=true
```
**Include CSRF token to Forms**

CSRF protection is only needed in ``post `` requests. So, you need to include the CSRF token as a hidden input element in every ``form:post`` forms. To do that just call ``_csrf()`` function.

```php
<form action="<?= ROUTER->postRoute('route.name') ?>" method="Post">
<?php _csrf() ?>
<!-- other input fields and submit button here -->
</form>
```
