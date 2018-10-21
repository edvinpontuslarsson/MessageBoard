# MessageBoard

## Description

[Message Board Website](http://youedvin.com/ "Youedvin")

An open source message board single page web application with full CRUD functionality developed with PHP and MySQL. 

## Features

### Implemented

Anybody can read the posted messages, only registered users can post messages and only the message authors can edit or delete their own messages. 

The message board also contains a login and registration system that fulfill most of [these test cases](https://github.com/dntoll/1dv610/blob/master/assignments/A2_resources/TestCases.md "Test Cases"). See which of those test cases are not fulfilled in the “Not Implemented” section.

### Not Implemented

Out of [these test cases](https://github.com/dntoll/1dv610/blob/master/assignments/A2_resources/TestCases.md "Test Cases") related to login with cookies, 3.3, 3.4, 3.5, 3.7 and 3.8 are not yet implemented.

So that means it is currently not possible for users to remain logged in after closing the browser. There is a column for temporary passwords in the Users MySQL-table that could be used for login with cookies. But that is a feature that is not yet implemented. 

Test Cases 1.8.1 and 3.6 are also not implemented, see more about that in the “Bugs” section.

Beyond that, the message board is currently quite simple and could be improved in many ways. 

#### Some potential security features: 
* registration verification by email 
* enabling changing password
* enabling restoring forgotten password
* enabling 2-step verification
* protection against brute force attacks
* protection against ddos attacks
* protection against cross site request forgery
* protection against cross site scripting

#### Some Potential application features: 

* not list all uploaded messages at once on the start page
* ability to see only messages posted by a specific user
* search functionality, enable image uploads
* enable comments

### Present Bugs

The application does not respond properly to reloads with resend of information, doing so should remove previous feedback but that is currently not the case. 

There is currently no protection against session hijacking. 

## Set-Up Instructions

### Necessary Installations

To run this application locally, you need to have a LAMP, WAMP or MAMP server installed with PHP 7.2 (or more recent), apache2 and MySQL. 

You also need to have the following line uncommented (beginning semicolon removed) from the php.ini file:
` extension=mysqli `
Restart server after making that change.

### Secret Environment.php File Set-Up

You need a file called Environment.php in the root folder containing the following code (but replace the `<examples>`):

```php
<?php

class Environment {
    private $isProduction = <true/false>;
    private $indexUrl = "<index url, e.g. />"; 
    private $hostname = "<your host, e.g. localhost>"
    private $mysqlUsername = "<your mysql username>";
    private $mysqlPassword = "<your mysql password>";
    private $databaseName = "<your database name>";

    public function isProduction() : bool {
        return $this->isProduction;
    }

   public function getIndexUrl() : string {
        return $this->indexUrl;
    }

    public function getHostName() : string {
        return $this->hostname;
    }

    public function getMysqlUsername() : string {
        return $this->mysqlUsername;
    }

    public function getMysqlPassword() : string {
        return $this->mysqlPassword;
    }

    public function getDatabaseName() : string {
        return $this->databaseName;
    }
}
```

This file should be kept secret, make sure to have it in .gitignore

### MySQL Set-Up

[MySQL tutorial](https://www.digitalocean.com/community/tutorials/a-basic-mysql-tutorial "MySQL tutorial")

Create a database with a name of your choice (that you define in your Environment.php file).

Using that database, you can create a table called "Users" with the necessary columns by querying this command into the database:

```sql
create table Users (
id INT(7) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
username VARCHAR(25) NOT NULL,
password VARCHAR(1000) NOT NULL,
temporarypassword VARCHAR(1000) NOT NULL,
permanentsecret VARCHAR(1000) NOT NULL
);
```

I have set the maximum password length to 1000 characters here (because a maximum length is needed for MySQL), users can use passwords of any length (above 6 characters) and the passwords will of course be hashed before being stored. This application currently uses the php default hash. 

You can create a table called "Blogs" with the necessary columns by querying this command into the database:

```sql
create table Blogs (
id INT(7) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
username VARCHAR(25) NOT NULL,
blogpost VARCHAR(10000) NOT NULL
);
```

## Testing

[Automated testing of the login system](http://csquiz.lnu.se:25083/index.php "1dv610 Assignment check")

You can test the rest of the message board application with for example postman. See the table below for instructions on how you can test the application with postman:

**Scenario**|**Postman Set-Up**|**Expected Result**
:-----:|:-----:|:-----:
Register a test user | Send a post request to the register page (e.g. http://youedvin.com/?register) with the following 4 keys and values in the Body (but you can replace the `examples`): _Key1_: RegisterView::UserName _Value1_: `myExampleUsr1` _Key2_: RegisterView::Password _Value2_: `myExamplePass` _Key3_: RegisterView::PasswordRepeat _Value3_: `myExamplePass` _Key4_: DoRegistration _Value4_: Register | Response with the login form with the message "Registered new user" and the username prefilled in the form

Log in as that test user | Send a post request to the start page (e.g. http://youedvin.com/) with the following 3 keys and values: _Key1_: LoginView::UserName _Value1_: `myExampleUsr1` _Key2_: LoginView::Password _Value2_: `myExamplePass` _Key3_: LoginView::Login _Value3_: login| You should now be logged in with a session

Post message | ... | ...

Edit message | ... | ...

Delete message | ... | ...

Post another message and save id values to edit and delete | edit_blog, delete_blog | ...

Log out | ... | ...

Register a second test user | tester2, banana | Log in form rendered with username prefilled

Log in as second test user | ... | logged in, session cookie

Get Another user's Edit message page | ... + saved id variable | ...

Get Another user's Delete message page | ... + saved id variable | ...

Post to Another user's Edit message | ... + saved id variable | ...

Post to Another user's Delete message | ... + saved id variable | ...