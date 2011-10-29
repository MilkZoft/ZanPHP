Introduction
------------------------
ZanPHP is an agile Web application development framework written in PHP5 that uses different design patterns and best practices to create applications more quickly with good quality code.

Some of the advantages of ZanPHP are:

    Easy to install and configure
    Easy to learn
    You can create almost any type of Web applications
    Compatible with different platforms
    Object oriented
    Use lots of design patterns and best practices to produce higher quality code
    Scalable applications and easy to maintain
    Documentation in English and Spanish
    Integration with jQuery and jQuery Mobile, Ajax and other new technologies
    It is free software 

Server Requirements
-------------------------
    PHP 5.1.X or higher
    Databases supported: MySQL, MySQLi, MsSQL, Oracle, PostgreSQL and SQLite 

Credits
-------------------------
ZanPHP was developed by Carlos Santana (CEO of MilkZoft). ZanPHP was developed in 2009, but was until July 2011 that took the decision to release as free software.
Currently developing ZanPHP is maintained by the development team and contributors of MilkZoft community.
Is achieved by implementing best practices to develop applications faster and with higher quality. 

Getting Started
-------------------------
To start working with ZanPHP, the first necessary step is to download it. Get ZanPHP is easy, simply download it from: https://github.com/MilkZoft/ZanPHP or git clone with the command:

git clone git@github.com:MilkZoft/ZanPHP.git

The next step is to set a constants of 2 configuration files. These files are located at: /core/config

The first file to modify is the /core/config/config.constants.php which should change the following constants:

    _domain: serves to specify the domain that our site will have.
    _modRewrite: toggles the stylization of URLs (TRUE or FALSE).
    _webURL: URL allows you to configure the general site will useful to load images, scripts, etc.
    _webCharacters: allows to show the HTML with or without format (TRUE or FALSE).
    _defaultApplication: allows to configure the default web application
    _webState: toggles the access to the website (Active or Inactive)
    _webLanguage: configure the default language of the website 

The second file to configure is /core/config/config.database.php in the following constants:

    _dbController: allows us to specify the database driver to use.
    _dbHost: allows us to configure the host where the database server (usually localhost).
    _dbUser: the user to connect to the database.
    _dbPwd: password to connect to the database.
    _dbName: the name of the database to which we will connect.
    _dbPort: database port.
    _dbPfx: the prefix our tables have. 

Once these steps, we have ZanPHP ready to work, just a matter of starting to create applications. 

Model View Controller
-------------------------
ZanPHP implements one of the design patterns used today by most web development frameworks. This pattern, referred to as MVC (Model View Controller), this means that basically the development is divided into 3 main parts: a model, a view and a controller. The following explains each concept in the MVC.

Model

This class will contain all the functions entrusted to interact with the business strategy of our application and therefore the handling of data, in more technical terms, will carry out all treatment on our application data handle (queries , inserts, updates and deleted). All these functions must be requested by either the driver or the other depending on the model, as the case may be.

View

The views are responsible for displaying data to the user, but can also be used to request data, of course, this according to what needs to be done. In the MVC applied to web development can be described as responsible for rendering HTML data.

Controller

This class is the administrator of the application flow, is responsible for receiving user requests, identify, process, send call the methods of the model and return data to the user through a view.

In ZanPHP the MVC applies within each application, you must create two files in the folder of the application: model.application.php controller.application.php and also the views should be within the "views" of each application , you must be named using the following convention: view.myview.php. 

Files Structure
-------------------------
www

This directory is one that contains all the applications developed with ZanPHP, plus include library files, there are different types of files, among which are: style sheets, external libraries, templates, images, and the uploads for each application.

zan

This directory is one that contains all the applications developed with ZanPHPEl directory "zan" is the most important ZanPHP, as found in the configuration files of the site to develop as well as important framework classes that support the architecture same, the translations, as well as all functions (helpers) that the framework offers to do the heavy lifting.

Configuration
--------------------------
ZanPHP requires slightly configuration to make it work properly for it, you have to edit 2 files in /core/config, these are: config.constants.php and config.database.php.

Note that the configuration is based on PHP constants, and according to these values ​​is set as the operation of the framework.

The first configuration file to analyze is: config.database.php, once the file is open you can see that this is the configuration needed to connect to the database to be used by the site. This file allows us to modify the constants for 2 areas of work, local work, or working remotely. The constants are:

    _dbController: allows us to specify the database driver to use.
    _dbHost: allows us to configure the host where the database server (usually localhost).
    _dbUser: the user to connect to the database.
    _dbPwd: password to connect to the database.
    _dbName: the name of the database to which we will connect.
    _dbPort: database port.
    _dbPfx: the prefix our tables have. 

The second configuration file, will all those config.constants.php constant for the operation of our site, that is general constant, noting that there are some important constants configured for the proper functioning of the site and the framework, these include:

    _domain: serves to specify the domain that our site will have.
    _modRewrite: toggles the stylization of URLs (TRUE or FALSE).
    _webURL: URL allows you to configure the general site will useful to load images, scripts, etc. 

Databases
--------------------------
Thanks to the design patterns implemented by ZanPHP, you can use more than one driver database on the websites, this means that we can work on a website under MySQL, SQL Server and PostgreSQL.

For this, it is only necessary, modify the constant _dbController located in the file: /core/config/config.database.php. 

Applications
--------------------------
Create an application with ZanPHP is very easy, you just need to create a directory within /applications, the directory must take the application name in lowercase. Note that the directory name that is the same with which you will access the application from the website.

Other aspects to consider, is that within the application directory 2 files should be mandatory, as well as a directory, which are: controller.application.php, model.application.php and /views.

The contents of these files and the directory will be explained in the subsequent topics. 

Controllers
--------------------------
The controllers are very important in ZanPHP they are indispensable for the use of MVC. It is important that each application must have a controller, a model and the views can vary.

However, this section will analyze the structure that a controller must have for proper operation:

    class MyApplication_Controller extends ZP_Controller {
     
        public function __construct() {
            $this->Templates = $this->core("Templates");
     
            $this->MyApplication_Model = $this->model("MyApplication_Model");
     
            $this->helpers();
     
            $this->application = $this->app("myapplication");
     
            $this->Templates->theme(_webTheme);
        }
     
        public function index() {
            print __("Hi, I'm MyApplication");
        }
     
    }

This is the basic structure that any controller should have and fulfill for the proper functioning of the framework.

Views
----------------------------
The views are responsible for showing or request data to the user. The controller is responsible for loading the view with data obtained by the models. That's how the MVC design pattern works.

Views should be named as follows: view.myview.php and must be located in the /views of each application.

Another important aspect to mention about the directory /views, is that this can be created within the /css, /js or /images, where can be placed style sheets, scripts and images that will be used only by the application. 

Models
----------------------------
Models are a fundamental part of an application, since they are in charge of interacting with the database, model, classify and to organize data.

Like the controllers, models have a basic structure that must be met to work correctly with ZanPHP:

    class MyApplication_Model extends ZP_Model {
     
        public function __construct() {
            $this->Db = $this->db();
     
            $this->helpers();
     
            $this->table = "mytable";
        }
     
    }

This is the basic structure that any controller should have and fulfill for the proper functioning of the framework. 

Translations
----------------------------
ZanPHP a user-friendly translations system, you only need to use a function and a file where you place the translations.

The function is used as follows:

    <?php print __("Some text to translate"); ?>

To complete the translation process, you must add the translation in the following file /core/languages/language.spanish.php, you must add as follows:

    function translation($text) {
        switch($text) {
            case "Some text to translate": return "Algún texto para traducir"; break;
        }
     
        return $text;
    }