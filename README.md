# WHAT IS PHPVISA
PHPVISA is a very lightweight PHP framework designed by Cedric Che-Azeh. This tool is designed for developers working on API-based projects with [blogvisa](https://blogvisa.com), but it is made available for public use under the [MIT License](https://opensource.org/licenses/MIT).


## Release Information
This repo contains in-development code for future releases. To download the latest release, query the branches and checkout to the branch carrying information for the latest version

## Server Requirements
PHPVISA works with PHP 8.0 or newer.

It could very well still work with PHP 7.3 or newer, but this is not recommended.

## Licensing
PHPVISA is licensed under the [MIT License](https://opensource.org/licenses/MIT)

## Purpose
PHPVISA was built primarily for *control*. It does not purport to be a better alternative to any other existing frameworks. What makes it similar to popular PHP frameworks is its high level of abstraction, modularity and lose coupling. It's however different from them in its extremely light-weight nature.

With PHPVISA, your overall coding experience won't be extremely shortened, but you would be able to have control over much of the code base which constitutes your applications, while maintaining a good level of modularity throughout. You'd have to be versed with object-oriented PHP to use PHPVISA

## Installation
Installing PHPVISA is as easy as cloning it from its [github repository](https://github.com/che-azeh/phpvisa.git). 


# DOCUMENTATION
The purpose of this documentation is to give you a good, high-level overview of how the PHPVISA framework works. As you would notice, it's very simple and straightforward, and does not contain any numerous in-built functions or parameters. Once you master how the different parts piece together, kicking off is pretty simple

## Request Lifecycle 
The first step to entry into the framework is the api/index.php file. This file handles all requests to all parts of the application, and routes them appropriately. It is the part of the application where you handle access control for your API and all routing mechanisms.

## Routing
On the entry file at api/index.php, we implement a simple routing mechanism. All requests to the API should be mapped in an array, where the key defines an endpoint relative to the API root, and the value defines the file to be accessed for that request.  So let's suppose your API root is https://example.com, if you make a GET request to https://example.com/login, the router would fetch the file whose key in the array is login. Note that the files contained in the value are equally relative to the API root. Thus, if the value of the _login_ key is login.php, you'd be deling with all classes within the _SERVER_ROOT_/login.php

The api/index.php file already contains two sample requests, *register* and *login*, which are routed to *register.php* and *login.php* respectively. These exist to give you an illustration of how the file works.

## Globals
As previously mentioned, the purpose of this framework isn't to hardcode configuration details for you. You're given huge control of what you'd be implementing. So even though our globals are contained in the the _sitewide-page.inc.php_ file, you are free to define them even in the routing file, if it'll make more sense to you.

The _sitewide-page.inc.php_ file is a file which is (or better still, has to be) inherited in every file an API request is made to. This file defines global variables and functions *which are needed in all classes of our application*. A few important global variables worth mastering are:

- *PATH_ROOT*: This variable defines the *root resource* of the application. This value should change depending on the server you're deploying the application to
- *PATH_HOME*: This variable defines the *home path* of the API. This is principally the API root to which all requests would be channelled.
- *PATH_ME*: This variable contains the path to all protected resources which need a login session to be accessed.
- *PATH_COMMON* is the path to all controllers and models which are needed and thus inherited throughout the application.
- *PATH_COMMON_MODULE* defines the path to all API-wide models used and thus inherited throughout the application.
- *PATH_COMMON_DATA_MANAGER* defines the path to all data-request resources on the application. This path holds the files containing all database access and queries.
- *PATH_RESOURCE_IMG* defines the path to images used as a resource requested by other files on the server (as opposed to files on the front-end).
- *PATH_IMG* defines the path to images sent to front-end applications.
- *API_KEY* where you define the API KEY of your app which would be shared with front-end developers.

The sitewide-page.php equally contains the important *verify_user()* function whose aim is to verify the reset tokens sent with each API request, so as to protect against session hijacking.

As you would notice, there have been other server-wide functions which have been added to this file, like *generate_random_string()*, *format_time()*, *query_to_url()* etc. These are functions you don't typically need, but are added here to illustrate our purpose of making the file act as a container for globals. Given all subsequent controllers and models inherit this file, you should typically place all your globals here.

## Controllers and Models and Data Managers
Our main controller is called _page.inc.php_ and is found at the root of the framework. The only file which inherits this controller is another controller file, _sitewide-page.inc.php_ which we saw above. All subsequent controllers inherit this one, and follow a naming convention where the name of the file must be followed by _-page.inc.php_, like the _sitewide-page.inc.php_ which we observed above.

Our main model is contained within the _common/modules/module.inc.php_ file. All models inherit the class within this file, and have a naming convention where the name of the file must be followed by _-module.inc.php_.

## Inheritance
PHPVISA uses a simple inheritance model. Understanding our file naming conventions would help you understand how we handle inheritance. All controllers are inherited by files having the _-page.inc.php_ ending. Given every endpoint we work with inherits the sitewide-page.inc.php file, we already included this file within the _api/index.php_ file which we saw above. Subsequent files would only inherit the *SitePage* class contained within this file.

Access to various endpoints would typically be controlled within different folders. Say for example, if we have an e-commerce application with users and products, it would make sense to add all controllers handling users within a _users_ folder, and all controllers handling products within a _products_ folder. This isn't a constraint, however. Remember, you're in control.

Whenever an endpoint exists within a sub-directory, we need a sub-class to *extend* the main *SitePage* class present in the _sitewide-page.inc.php_ file. Within the framework, we've added a _*me*_ directory. This directory is meant to be accessed by authenticated users. It contains a file called _sectionwide-page.inc.php_. This file is the parent controller for all access to protected resources. The *SectionPage* class within this file accurately inherits the *SitePage* class. The first thing it does within its controller is to check if the user's reset token which we saw above checks out, through the *verify_user* function.

Inheritance is equally observed from sub-folders within this _me_ folder. We've included a sample _me/users_ folder for illustration. A user's folder can have several endpoints, for example, one for getting all users, another for getting specific users, etc. How you define the files to handle these is up to you. However, you can equally define another _sectionwide-page.inc.php_ file within such a sub-folder. The purpose of this file would be to define general functions needed for access by all files within the _me/users_ folder. This way, any other classes outside this folder won't need to have access to such functions. If you nest folders, you'd have to equally define _sectionwide-page.inc.php_ files which further serve the same purpose.

To bring all these together, all controllers within sub-directories inherit from their _sectionwide-page.inc.php_ which inherit from all their parent _sectionwide-page.inc.php_ files, which inherit from the _sitewide-page.inc.php_ file.

## Data Access and Manipulation
By data access and manipulation, we are talking of all operations with the database. Database credentials are defined in the _common/data-managers/data-manager.inc.php_. This file should be modified whenever you change the server hosting your database. 

Within controllers, there are 2 functions inherited which let you access and manipulate data (*save_data()* and *load_data()*). These are all inherited from _sectionwide-page.inc.php_. Understanding inheritance, it should go without saying that if you call the function within the _sectionwide-page.inc.php_ file, the data called would be accessible to the entire application. If you call it within the _me/sectionwide-page.inc.php_ file, the data would be accessible to all classes within that directory. Similarly, if you call it within the _me/users/sectionwide-page.inc.php_, it would be accessible to all classes defined infiles within the _me/users_ directory.

*save_data()* is used to execute POST, PUT and DELETE commands on the database, while *load_data()* is used to execute GET commands. 

### save_data()
This function should be called when we wish to modify the database. It should have 3 variables, all inherited from the main controller file, _page.inc.php_. These variables are *save_args, save_data* and *save_stat*. *save_args* is an array of arrays containing the queries to execute on the database, *save_data* is an array of arrays which would store any response received from the database after executing the commands in *save_args*. *save_stat* is an array of arrays containing error and success responses returned from the database after executing the command in _save_args_.

Given PHPVISA uses PHP Data Objects with MYSQL (PDO_MYSQL), all statements are prepared. As multi-dimensional arrays, the above variables would contain identical array keys for corresponding queries, data and response codes from database queries. So for example, a query to retrieve users would have *_$save_args['get_users']_*, *_$save_data['get_users']_* and *_$save_stat['get_users']_* respectively corresponding to the variables to hold database query arguments, response data and status codes.

As is normal with object-oriented PHP, the *save_data* function should be inherited from all child classes. So for example, within a _sectionwide-page.inc.php_, inherit the function from its parent (which might be _sitewide-page.inc.php_), which in turn would inherit it from its parent (which would be _page.inc.php_). Without this inheritance, whatever data query or modification you did in a parent class won't be inherited in its child.

### load_data()
If you understood how the *save_data()* function works, you'd very easily understand how the *load_data()* function equally works. Its own corresponding variables are named *load_args*, *load_data* and *load_stat*

The main difference between this function and *save_data()* lies in the structure of retrieved data. When you esecute a query from say a *_load_args['users']_* array, the corresponding *_load_data['users']_* array of results would have the returned rows organized in a multi-dimensional array with numeric keys each containing data from each row returned from the database. The array value of each key would in turn have keys corresponding to the column name returned from the database, and values corresponding to the row names.

## Conclusion
This is a basic rundown of the PHPVISA framework. At this point, you should take a look at our guided tutorials to see all these explanations in practice.