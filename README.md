                                                                                                         
          ██████╗ ██╗  ██╗██████╗     ██╗      █████╗ ██╗   ██╗███╗   ██╗ ██████╗██╗  ██╗███████╗██████╗ 
          ██╔══██╗██║  ██║██╔══██╗    ██║     ██╔══██╗██║   ██║████╗  ██║██╔════╝██║  ██║██╔════╝██╔══██╗
          ██████╔╝███████║██████╔╝    ██║     ███████║██║   ██║██╔██╗ ██║██║     ███████║█████╗  ██████╔╝
          ██╔═══╝ ██╔══██║██╔═══╝     ██║     ██╔══██║██║   ██║██║╚██╗██║██║     ██╔══██║██╔══╝  ██╔══██╗
          ██║     ██║  ██║██║         ███████╗██║  ██║╚██████╔╝██║ ╚████║╚██████╗██║  ██║███████╗██║  ██║
          ╚═╝     ╚═╝  ╚═╝╚═╝         ╚══════╝╚═╝  ╚═╝ ╚═════╝ ╚═╝  ╚═══╝ ╚═════╝╚═╝  ╚═╝╚══════╝╚═╝  ╚═╝
                                                                                                         

It is a pseudo-framework for PHP developers to get a quick start with their projects with the highest control and lowest learning curve!


## File Structure

```
|- project_name/
|   |- core/ <- the files that you'd never have to think about
|   |   |- .htaccess
|   |   |- index.php
|   |- shell/ <- the files that work as customizers and else
|   |   |- errors/
|   |   |- gaurds/
|   |   |- preproccesors/
|   |   |- routes/
|   |   |- .env.example
|   |- facade/ <- the files that you will work with
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
* Executable: `php launch.php ___`
    * Setup
    * Configuration
    * Help
    * Plugin **[Planned for future]**
