# MessageBoard

## Table of contents

* [Description](#description)
* [Features](#features)
    * [Implemented](#implemented)
    * [Not Implemented](#not-implemented)
    * [Present Bugs](#present-bugs)
* [Set-Up Instructions](#set-up-instructions)
    * [Necessary Installations](#necessary-installations)
    * [Secret Environment.php File Set-Up](#secret-environmentphp-file-set-up)
    * [MySQL Set-Up](#mysql-set-up)
* [Testing](#testing)
    * [Latest test status](#latest-test-status)

## Description

[Message Board Website](http://youedvin.com/ "Youedvin")

An open source message board single page web application with full CRUD functionality developed with PHP and MySQL. 

## Features

### Implemented

Anybody can read the posted messages, only registered users can post messages and only the message authors can edit or delete their own messages. 

The message board also contains a login and registration system that fulfill most of [these test cases](https://github.com/dntoll/1dv610/blob/master/assignments/A2_resources/TestCases.md "Test Cases"). See which of those test cases are not fulfilled in the “Not Implemented” section.

### Not Implemented

Out of [these test cases](https://github.com/dntoll/1dv610/blob/master/assignments/A2_resources/TestCases.md "Test Cases") related to login with cookies, 3.3, 3.4 and 3.5 are not yet implemented.

So that means it is currently not possible for users to remain logged in after closing the browser. Users get a cookie with randomly generated string as password if they want to stay logged in, but those cookies are currently not being handled to enable the users to stay logged in. There is a column for temporary passwords in the Users MySQL-table that could be used for login with cookies.

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

#### Test case 1 - Register a test user
Send a post request to the register page (e.g. http://youedvin.com/?register) with the following 4 keys and values in the Body form-data (but you can replace the `examples`): <br>
_Key1_: RegisterView::UserName _Value1_: `myExampleUsr1` <br>
_Key2_: RegisterView::Password _Value2_: `myExamplePass` <br>
_Key3_: RegisterView::PasswordRepeat _Value3_: `myExamplePass` <br> 
_Key4_: DoRegistration _Value4_: Register <br>

#### Expected Result test case 1
Response with the login form with the message "Registered new user" and the username prefilled in the form

#### Test case 2 - Log in as that test user
Send a post request to the start page (e.g. http://youedvin.com/) with the following 3 keys and values in the Body form-data: <br>
_Key1_: LoginView::UserName _Value1_: `myExampleUsr1` <br>
_Key2_: LoginView::Password _Value2_: `myExamplePass` <br>
_Key3_: LoginView::Login _Value3_: login <br>

#### Expected Result test case 2
You should now be logged in with a session

#### Test case 3 - Post message as that test user
Make a new request. Copy the value of the php session from the Cookies from the previous post request, in the Headers of new request, put PHPSESSID as key and paste the copied value as value. Put the following 2 keys and values in the Body form-data: <br>
_Key1_: blog-input _Value1_: `Hello World!` <br>
_Key2_: blog-post _Value2_: Submit <br>

#### Expected Result test case 3
You should see the posted message at the top of the messages in the response body

#### Test case 4 - Edit message
Make a new request. Put the session key and value of new post request like we did with the previous request. Copy the link to edit the message from the response body html in the previous request. Write the start page url followed by pasting the copied link in the field for the request URL in postman (e.g. `http://youedvin.com/?edit_blog=4`) but replace the 4 with the correct number. Put the following 3 keys and values in the Body form-data: <br>
_Key1_: blog-input _Value1_: `Hello Mars!`<br>
_Key2_: edit-blog-ID _Value2_: `4` // but probably not 4, the correct number in your case<br>
_Key3_: blog-edit-post _Value3_: Update<br>

#### Expected Result test case 4
The message should now have been edited (see the response Body html)

#### Test case 5 - Delete message

Make a new request and do like previously with the session. Write the start page url followed by pasting the copied link in the field for the request URL in postman (e.g. `http://youedvin.com/?delete_blog=4`) but replace the 4 with the correct number. Put the following 2 keys and values in the Body form-data:<br>
_Key1_: delete-blog-ID _Value1_: `4` // but probably not 4, the correct number in your case<br>
_Key2_: blog-delete-post _Value2_: Yes delete it<br>

#### Expected Result test case 5
The message should now have been deleted (see the response Body html)

#### Test case 7 - Post another message
Repeat test case 3, post message. Save the urls to edit and delete post. 

#### Test case 8 - Log out
Make a new request and do like previously with the session. Write the start page url in the field for the request URL in postman (e.g. http://youedvin.com/). Post with the following key and value in the Body form-data:<br>
_Key1_: LoginView::Logout _Value1_: logout

#### Test case 9 - Register a second test user
Repeat test case 1, register user, but with a different username.

#### Test case 10 - Login with the second test user
Repeat test case 2, login user, but with the new username.

#### Test case 11 - make a get request to another user's edit message page
Make a new request and do like previously with the session but with the new session. Make a get request to the saved url for editing the message by the previous user. E.g. `http://youedvin.com/?edit_blog=6` but replace the 6 with the correct number.

#### Expected Result test case 11
A 403 forbidden response. 

#### Test case 12 - make a get request to another user's delete message page
Just like with test case 11 but for delete.

#### Test case 13 - try to edit another user's message
Do like in test case 4, edit message, but with a url for a message by another user.

#### Expected Result test case 13
A 403 forbidden response. 

#### Test case 14 - try to delete another user's message
Just like with test case 14 but for delete.

### Latest test status

85 % on the [Automated testing of the login system](http://csquiz.lnu.se:25083/index.php "1dv610 Assignment check")

All 14 postman message board tests passed.