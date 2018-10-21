# MessageBoard

To run this application locally, you need to have php 7.2 (or higher), apache2, MySql, php-mysql installed as well as a MySQL-database prepared with a database name as well as a table called "Users" that you can create by querying this command into the database:

```sql
create table Users (
id INT(7) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
username VARCHAR(25) NOT NULL,
password VARCHAR(1000) NOT NULL,
temporarypassword VARCHAR(1000) NOT NULL,
permanentsecret VARCHAR(1000) NOT NULL
);
```

https://www.digitalocean.com/community/tutorials/a-basic-mysql-tutorial

I have set the maximum password length to 1000 characters here (because a maximum length is needed for mysql), users can post passwords of any length (above 6 characters) and the passwords will of course be hashed before being stored. This application currently uses the php default hash. 

You will also need a table called "Blogs" that you can create with this command:

```sql
create table Blogs (
id INT(7) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
username VARCHAR(25) NOT NULL,
blogpost VARCHAR(10000) NOT NULL
);
```

You also need to have the following line uncommented (beginning semicolon removed) from the php.ini file:
extension=mysqli
Restart server after making that change. 

You also need a file called Environment.php in the root folder with the following code (but replace the `<examples>`):

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
