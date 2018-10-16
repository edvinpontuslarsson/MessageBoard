# Login_1DV610

To run this application locally, you need to have php 7.2 (or higher), apache2, MySql, php-mysql installed as well as a MySQL-database prepared with a database name as well as a table called "Users" that you create by querying this command into the database:

`create table Users (`
`id INT(7) UNSIGNED AUTO_INCREMENT PRIMARY KEY, `<br/>
`username VARCHAR(25) NOT NULL,`<br/>
`password VARCHAR(1000) NOT NULL,`<br/>
`temporarypassword VARCHAR(1000) NOT NULL,`<br/>
`permanentsecret VARCHAR(1000) NOT NULL`<br/>
`);`

I have set the password length to 1000 characters here, users can post passwords of any length (above 6 characters) and the passwords will of course be hashed before storing. This application currently uses the php default hash. 

You also need to have the following line uncommented (beginning semicolon removed) from the php.ini file:
`extension=mysqli`<br/>
Restart server after making that change. 

You also need an environment.php file in the root folder with the following code (but replace the `<examples>`):

`<?php`


`putenv('host=<your host, e.g. localhost>');`<br/>
`putenv('username=<your database username>');`<br/>
`putenv('password=<your database password>');`<br/>
`putenv('db=<your database name>');`<br/>

