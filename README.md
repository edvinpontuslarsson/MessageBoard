# Login_1DV610

To run this application, you need to have php 7+, apache2, MySql, php-mysql installed as well as a MySQL-database prepared with a database name as well as a table called "Users" with these columns:

`id INT(7) UNSIGNED AUTO_INCREMENT PRIMARY KEY, `
`username VARCHAR(25) NOT NULL,`
`password VARCHAR(128) NOT NULL,`
`reg_date TIMESTAMP`

You also need to have the following line uncommented (beginning semicolon removed) from the php.ini file:
`extension=mysqli`<br/>
Restart server after making that change. 

You also need an environment.php file in the root folder with the following code (but replace the `<examples>`):

`<?php`


`putenv('host=<your host, e.g. localhost>');`<br/>
`putenv('username=<your database username>');`<br/>
`putenv('password=<your database password>');`<br/>
`putenv('db=<your database name>');`<br/>

