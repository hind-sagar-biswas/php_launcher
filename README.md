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

## File Structure

```
|- project_name/
|   |- core/ <- the files that you'd never have to think about
|   |   |- .htaccess
|   |   |- index.php
|   |   |- init.php
|   |- shell/ <- the files that work as customizers and else
|   |   |- Database/
|   |   |   |- Seed/
|   |   |   |- Table/
|   |   |   |- list.php
|   |   |- errors/
|   |   |   |- api.php
|   |   |   |- web.php
|   |   |- gaurds/
|   |   |   |- api.php
|   |   |   |- web.php
|   |   |- routes/
|   |   |- .env.example
|   |- facade/ <- the files that you will work with
|   |   |- api/ <- the api files
|   |- .gitignore
|   |- .htaccess
|   |- composer.json
|   |- composer.lock
|   |- launch.php
```

## Planned features

    * Router:
       * Routing system:
           * File Routing _<-- default_
           * Web Routing
           * API Routing
       * Security:
           * Csrf protection _<-- default_
           * Diff Methods
           * Auths
       * ERROR Pages
    * Database:
       * Modes:
           * Single Database _<-- default_
           * Multiple Database **[Planned for future]**
       * Database types
           * MySQL _<-- default_
           * SQlite3 **[Planned for future]**
       * Query Builder
       * Migration
       * Seeding
    * Authentication:
       * Login / Logout / Registration
       * RememberMe with Cookies
    * API:
       * Output as JSON
       * Authentication
    * Executable: `php launch ___`
       * Setup: `php launch`
       * Flush & setup DB: `php launch migrate flush`
       * Seed DB: `php launch migrate seed`
       * Dump DB: `php launch migrate dump`
       * Create listed Table File: `php launch migrate create`
       * Create single Table File: `php launch create table`
       * Configuration
       * Help
       * Plugin **[Planned for future]**
