# Login_1DV610

To run this application locally, you need to have php 7.2 (or higher), apache2, MySql, php-mysql installed as well as a MySQL-database prepared with a database name as well as a table called "Users" that you can create by querying this command into the database:

`create table Users (`<br/>
`id INT(7) UNSIGNED AUTO_INCREMENT PRIMARY KEY, `<br/>
`username VARCHAR(25) NOT NULL,`<br/>
`password VARCHAR(1000) NOT NULL,`<br/>
`temporarypassword VARCHAR(1000) NOT NULL,`<br/>
`permanentsecret VARCHAR(1000) NOT NULL`<br/>
`);`

https://www.digitalocean.com/community/tutorials/a-basic-mysql-tutorial

I have set the maximum password length to 1000 characters here (because a maximum length is needed for mysql), users can post passwords of any length (above 6 characters) and the passwords will of course be hashed before being stored. This application currently uses the php default hash. 

You will also need a table called "Blogs" that you can create with this command:

`create table Blogs (`<br/>
`id INT(7) UNSIGNED AUTO_INCREMENT PRIMARY KEY, `<br/>
`username VARCHAR(25) NOT NULL,`<br/>
`blogpost VARCHAR(10000) NOT NULL`<br/>
`);`

You also need to have the following line uncommented (beginning semicolon removed) from the php.ini file:
`extension=mysqli`<br/>
Restart server after making that change. 

You also need a file called Environment.php in the root folder with the following code (but replace the `<examples>`):

`<?php`

`class Environment {`<br/>
`    private $isProduction = <true/false>;`<br/>
`    private $indexUrl = "<index url, e.g. />";`<br/> 
`    private $hostname = "<your host, e.g. localhost>"`<br/>
`    private $mysqlUsername = "<your mysql username>";`<br/>
`    private $mysqlPassword = "<your mysql password>";`<br/>
`    private $databaseName = "<your database name>";`<br/>

`    public function isProduction() : bool {`<br/>
`        return $this->isProduction;`<br/>
`    }`<br/>

`   public function getIndexUrl() : string {`<br/>
`        return $this->indexUrl;`<br/>
`    }`<br/>

`    public function getHostName() : string {`<br/>
`        return $this->hostname;`<br/>
`    }`<br/>

`    public function getMysqlUsername() : string {`<br/>
`        return $this->mysqlUsername;`<br/>
`    }`<br/>

`    public function getMysqlPassword() : string {`<br/>
`        return $this->mysqlPassword;`<br/>
`    }`<br/>

`    public function getDatabaseName() : string {`<br/>
`        return $this->databaseName;`<br/>
`    }`<br/>
`}`<br/>

This file should be kept secret, make sure to have it in .gitignore
