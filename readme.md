## Tina4 - This is Not A Framework ##

Tina4 is a light-weight routing and twig based templating system which allows you to write websites and API applications very quickly.

The premise of the project is to make you the developer and PHP, the heroes!

**Beta Testing**

[Join our Slack Channel to participate and receive all the latest builds](https://docs.google.com/forms/d/e/1FAIpQLSdrapVxI-19DapgKKuhtlLyPc99SLg8Re2Lpn3PS_K0M2Rc7w/viewform)

**News**

*February 15, 2021* - Routing in large projects seems to be really messy and finding stuff is a pain.  To this end you can now direct your routing to class methods, they still behave the same as the anonymous methods but now make more sense for grouping functionality. Also added back in, the ability to generate ORM objects directly from your database using the command line tool.

*December 28, 2020* - We are getting close to a release point, there are still a number of bugs to be fixed though and things to be documented. PHP 8.0 is not in a good place for database use from what we've tested.

**Features**

- Auto templating
- Auto inclusions & project structure
- Annotations for quick Swagger documentation & security
- Annotations for tests, write unit tests as you code  
- Simple ORM
- Object Orientated HTML
- Service Runner
- Modular Programming

### Installing ###

*PHP 8.0 is not a stable candidate yet, for example some database functionlity is not completely supported*

- Install PHP7.1 > make sure the following extensions are enabled php_fileinfo, mbstring, curl.
- Install Composer * Windows users must install openssl so that the JWT keys will be generated correctly  
- Create a project folder where you want to work
- In your project folder terminal / console
```bash
composer require andrevanzuydam/tina4php
```
- Windows
```bash
vendor\bin\tina4
```
- Mac/Linux
```bash
vendor/bin/tina4
```

```bash
====================================================================================================
TINA4 - MENU 
====================================================================================================
1.) Create index.php
2.) Run Tests
3.) Create database connection
4.) Create orm objects
Choose menu option or type "quit" to Exit:
```

- Choose option 1 and press Enter, then type quit to exit, press Enter.


- Spin up a web server with PHP in your terminal in the project folder
```bash
php -S localhost:8080 index.php
```
- Hit up http://localhost:8080 in your browser, you should see the 404 error

### Quick Reference ###

The folder layout is as follows and can be overridden by defining PHP constants for ```TINA4_TEMPLATE_LOCATIONS```, ```TINA4_ROUTE_LOCATIONS``` & ```TINA4_INCLUDE_LOCATIONS```:

  * src
     * api (routing)
     * app (helpers, PHP classes)
     * assets (system twig files, images, css, js)
     * objects (ORM objects - extend \Tina4\ORM)
     * routes (app routing)
     * scss - style sheet templates  
     * services (service processes - extend \Tina4\Process)
     * templates (app twig files)
     
     
### .Env Configuration

Tina4 uses a .env file to setup project constants, a .env will be created for you when the system runs for the first time.
If you specify an environment variable on your OS called ENVIRONMENT then .env.ENVIRONMENT will be loaded instead.

```bash
[Section]           <-- Group section
MY_VAR=Test         <-- Example declaration, no quotes required or escaping, quotes will be treated as part of the variable
# A commment        <-- This is a comment
[Another Section]
VERSION=1.0.0
```
Do not include your .env files with your project if they contain sensitive information like password, instead create an example of how it should look.

### Example of Routing

Creating API end points and routers in Tina4 is simple as indicated below.  If you are adding swagger annotations, simply hitup the /swagger end point to see the OpenApi rendering.

```php
/**
* @description Swagger Description
* @tags Example,Route
*/
\Tina4\Get::add("/hello-world", function(\Tina4\Response $response){
    return $response ("Hello World!");
});
```

Routes can also be mapped to class methods, static methods are preferred for routing, but you can mix and match for example if you want to keep all functionality neatly together.

```php
/**
 * Example of route calling class , method
 * Note the swagger annotations will go in the class
 */
\Tina4\Get::add("/test/class", ["Example", "route"]);

```

Example.php
```php

class Example
{
    public function someThing() {
        return "Yes!";
    }
    
    /**
     * @param \Tina4\Response $response
     * @return array|false|string
     * @description Hello Normal -> see Example.php route
     */
    public function route (\Tina4\Response $response) {
        return $response ("OK!");
    }

}

```



### Change Log
```
2021-02-15 New! Routes can now be directed to Class methods, ORM generation available in tina4
2021-02-13 Fixes for Firebird database engine released
2021-01-10 SCSS building added
2020-12-28 MySQL fixes on error debugging
2020-12-25 Added named param binding for SQLite3
2020-12-19 Added Annotations for Unit Testing
2020-12-14 Fixes for MySQL not handling saving of nulls in bind_params
2020-12-08 Fixes for MySQL & ORM saving
2020-12-08 Fixes for isBinary under Utilities
```
